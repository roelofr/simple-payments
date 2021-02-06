<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Tests\Services;

use Illuminate\Support\Facades\Config;
use Money\Currency;
use Money\Money;
use Roelofr\SimplePayments\Facades\Util;
use Roelofr\SimplePayments\Models\Invoice;
use Roelofr\SimplePayments\Models\InvoiceLine;
use Roelofr\SimplePayments\Tests\TestCase;
use RuntimeException;

class UtilitiesTest extends TestCase
{
    public function test_get_default_currency(): void
    {
        Config::set('simple-payments.default-currency', 'USD');

        $default = Util::getDefaultCurrency();

        $this->assertInstanceOf(Currency::class, $default);
        $this->assertSame('USD', $default->getCode());

        $this->assertSame(Util::getDefaultCurrency(), $default);
    }

    /**
     * @dataProvider provideMonetaryValues
     */
    public function test_get_monetary_value($input, ?Money $output): void
    {
        if ($output === null) {
            $this->expectException(RuntimeException::class);
            Util::getMonetaryValue($input);
        }

        $this->assertEquals($output, Util::getMonetaryValue($input));
    }

    /**
     * @dataProvider provideMoneyValues
     */
    public function test_money_to_string(Money $input, string $output): void
    {
        $this->assertSame($output, Util::moneyToString($input));
    }

    public function test_determine_cheapest_rate(): void
    {
        $this->markTestIncomplete('TODO');
    }

    public function test_determine_value(): void
    {
        $this->markTestIncomplete('TODO');
    }

    public function provideMoneyValues(): array
    {
        return [
            [Money::EUR(0), '€0.00'],
            [Money::USD(123_40), '$123.40'],
        ];
    }

    public function provideMonetaryValues(): array
    {
        $invoice = new Invoice();
        $invoice->products = [
            (new InvoiceLine())
                ->setName('Steve')
                ->setPrice(Money::EUR(100_00)),
            (new InvoiceLine())
                ->setName('Steve too')
                ->setPrice(Money::EUR(69_42)),
        ];

        return [
            [$invoice, Money::EUR(169_42)],
            ['12345', Money::EUR(123_45)],
            [9876_54, Money::EUR(9876_54)],
            ['69420 JPY', Money::JPY(69_420)],
            ['0.01 USD', Money::USD(1)],
        ];
    }
}
