<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use LogicException;
use Money\Currency;
use Money\Money;
use Roelofr\SimplePayments\Contracts\Invoicable;
use Roelofr\SimplePayments\Models\Invoice;
use RuntimeException;

final class Util
{
    private static ?Currency $currency = null;

    public static function getDefaultCurrency(): Currency
    {
        return self::$currency ??= new Currency(Config::get('simple-payments.default-currency'));
    }
    /**
     * Returns the monetary value of the given object.
     * @param Invoicable|Invoice|mixed $object
     * @return Money
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    static public function getMonetaryValue($object): Money
    {
        // Invoicables, all items
        if ($object instanceof Invoicable) {
            $items = $object->getInvoiceLines()->pluck('price');
            return Money::sum(...$items);
        }

        // Invoices, without transfer fees
        if ($object instanceof Invoice) {
            return $object->value;
        }

        // Integers, as smallest currency
        if (is_int($object)) {
            return new Money($object, self::getDefaultCurrency());
        }

        // Strings, like "12345"
        if(is_string($object) && ctype_digit($object)) {
            return new Money($object, self::getDefaultCurrency());
        }

        // Currency format (12 EUR, 0.000034 XBC)
        if (is_string($object) && preg_match('/^(?<value>\d+(\.\d+)?)(?: (?<code>[A-Z]{3}))$?/', $object, $matches, PREG_UNMATCHED_AS_NULL)) {
            $currency = $matches['code'] ? new Currency($matches['code']) : Util::getDefaultCurrency();
            return new Money($matches['value'], $currency);
        }

        // Fail otherwise
        return new RuntimeException('Cannot determine monetary value of input');
    }

    /**
     * Converts money to string
     * @param Money $money
     * @return string
     */
    public function moneyToString(Money $money): string
    {
        throw new LogicException('Method not yet implemented');
    }
}
