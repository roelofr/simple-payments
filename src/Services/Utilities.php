<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Services;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\MoneyFormatter;
use Money\MoneyParser;
use Money\Parser\DecimalMoneyParser;
use NumberFormatter;
use Roelofr\SimplePayments\Contracts\Invoicable;
use Roelofr\SimplePayments\Contracts\PaymentProvider as PaymentProviderContract;
use Roelofr\SimplePayments\Models\Invoice;
use RuntimeException;

final class Utilities
{
    private const CURRENCY_REGEX = '/^(?<value>\d+(?:\.(?<decimals>\d+))?)(?: (?<code>[A-Z]{3}))?$/';

    private ?Currency $currency = null;

    private ?MoneyFormatter $formatter = null;

    private ?MoneyParser $decimalParser = null;

    public function getDefaultCurrency(): Currency
    {
        return $this->currency ??= new Currency(Config::get('simple-payments.default-currency'));
    }

    /**
     * Returns the monetary value of the given object.
     * @param Invoicable|Invoice|mixed $object
     * @return Money
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function getMonetaryValue($object): Money
    {
        // Invoicables, all items
        if ($object instanceof Invoicable) {
            $items = collect($object->getInvoiceLines())
                ->pluck('price')
                ->filter();

            return Money::sum(...$items);
        }

        // Integers, as smallest currency
        if (is_int($object)) {
            return new Money($object, $this->getDefaultCurrency());
        }

        // Strings, like "12345"
        if (is_string($object) && ctype_digit($object)) {
            return new Money($object, $this->getDefaultCurrency());
        }

        // Currency format (12 EUR, 0.000034 XBC)
        if (is_string($object) && preg_match(self::CURRENCY_REGEX, $object, $matches, PREG_UNMATCHED_AS_NULL)) {
            $currency = $matches['code'] ? new Currency($matches['code']) : $this->getDefaultCurrency();
            $decimals = $matches['decimals'];
            $value = $matches['value'];

            if ($decimals === null) {
                return new Money($value, $currency);
            }

            $this->decimalParser ??= new DecimalMoneyParser(new ISOCurrencies());

            return $this->decimalParser->parse($value, $currency);
        }

        // Fail otherwise
        throw new RuntimeException('Cannot determine monetary value of input');
    }

    /**
     * Converts money to string
     * @param Money $money
     * @return string
     */
    public function moneyToString(Money $money): string
    {
        if (! $this->formatter) {
            $numberFormatter = new NumberFormatter(Config::get('app.locale', 'en'), NumberFormatter::CURRENCY);
            $this->formatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());
        }

        return $this->formatter->format($money);
    }

    /**
     * Determines the lowest rate you would have to pay for this amount, optionally
     * using the provider assigned to $invoice.
     * @param Money $value
     * @param null|Invoice $invoice
     * @return Money
     */
    public function determineCheapestRate(PaymentProviderContract $provider, Money $price, string &$cheapestProvider): Money
    {
        $methods = $provider->getPaymentMethods($price);

        $lowest = $this->getMonetaryValue(100_000);
        $lowestMethod = null;

        foreach ($methods as $method) {
            $methodPrice = $method->getCost()->calculatePrice($price);
            if ($methodPrice->lessThan($lowest)) {
                $lowestMethod = $method;
                $lowest = $methodPrice;
            }
        }

        $cheapestProvider = $lowestMethod->getCode();

        return $lowest;
    }

    /**
     * Determines the Invoicable's total value
     * @param Invoicable $invoicable
     * @return Money
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function determineValue(Invoicable $invoicable): Money
    {
        // Get prices
        $items = collect($invoicable->getInvoiceLines())
            ->pluck('price')
            ->filter()
            ->toArray();

        // Return zero if empty
        if (empty($items)) {
            return $this->getMonetaryValue(0);
        }

        // Count the sum
        return Money::sum(...$items);
    }
}
