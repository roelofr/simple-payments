<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Tests\PaymentProviders;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Money\Money;
use Roelofr\SimplePayments\Contracts\PaymentRepository;
use Roelofr\SimplePayments\Enums\InvoiceStatus;
use Roelofr\SimplePayments\Exceptions\Logic\ReadOnlyException;
use Roelofr\SimplePayments\Facades\Util;
use Roelofr\SimplePayments\Models\Invoice;
use Roelofr\SimplePayments\Models\InvoiceLine;
use Roelofr\SimplePayments\Tests\TestCase;

class InvoiceTest extends TestCase
{
    public function test_basic_function(): void
    {
        $model = new Invoice();

        $model->products = [
            (new InvoiceLine())
                ->setName('Test 123')
                ->setPrice(Util::getMonetaryValue("100.00"))
                ->setDescription('Steve'),
        ];
        $model->price = Money::EUR(100);

        Date::setTestNow('2019-01-01 13:37:00+02:00');

        $model->save();

        $this->assertEquals(sprintf(Invoice::INVOICE_FORMAT, 2019, 1), $model->code);

        $this->assertCount(1, $model->products);
        $this->assertEquals("100", $model->price->getAmount());
    }

    public function tests_cast_conversion(): void
    {
        $model = new Invoice();

        $product = (new InvoiceLine())
            ->setName('Test 123')
            ->setPrice(Util::getMonetaryValue(500_00))
            ->setDescription('Steve')
            ->lock();

        $model->products = [$product];
        $model->price = Money::EUR(500_00);
        $model->status = InvoiceStatus::PAID;

        $model->save();

        $freshModel = $model::find($model->getKey());

        $this->assertEquals([$product], $freshModel->products);
        $this->assertTrue(Money::EUR(500_00)->equals($freshModel->price));
        $this->assertEquals(InvoiceStatus::PAID(), $freshModel->status);
    }

    public function test_find_code(): void
    {
        $model = Invoice::factory()->create();

        $modelByKey = Invoice::find($model->getKey());
        $modelByCode = Invoice::findByCode($model->code);

        $this->assertEquals(
            $modelByKey,
            $modelByCode
        );
    }

    public function test_readonly_guards(): void
    {
        $model = Invoice::factory()->create();

        $model->save();

        $model->products = [];

        $this->expectException(ReadOnlyException::class);

        $model->save();
    }

    public function test_invoicable_cast(): void
    {
        $model = Invoice::factory()->create();

        $this->assertEquals($model->getInvoiceLines(), $model->products);
    }

    public function test_provider_generation(): void
    {
        $repo = App::make(PaymentRepository::class);
        assert($repo instanceof PaymentRepository);

        $model = Invoice::factory()->create();
        assert($model instanceof Invoice);

        $this->assertEquals(
            $repo->provider($model),
            $model->getPaymentProvider()
        );
    }
}
