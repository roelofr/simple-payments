<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Tests\PaymentProviders;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Roelofr\SimplePayments\Contracts\PaymentProvider;
use Roelofr\SimplePayments\Contracts\PaymentRepository;
use Roelofr\SimplePayments\Exceptions\Logic\InvalidProviderException;
use Roelofr\SimplePayments\Exceptions\Runtime\ProviderNotFoundException;
use Roelofr\SimplePayments\Models\Invoice;
use Roelofr\SimplePayments\PaymentProviders\DummyProvider;
use Roelofr\SimplePayments\Tests\TestCase;
use Roelofr\SimplePayments\Tests\Traits\TestsInaccessibleProperties;

class RepositoryTest extends TestCase
{
    use TestsInaccessibleProperties;

    public function test_valid_configuration()
    {
        Config::set([
            'simple-payments.default-provider' => 'dummy',
            'simple-payments.providers' => [
                'dummy' => [],
            ],
        ]);

        $repo = App::make(PaymentRepository::class);
        assert($repo instanceof PaymentRepository);

        $this->assertEquals(['dummy'], $repo->availableProviders());
        $this->assertEquals('dummy', $repo->name());
    }

    public function test_aliassing_classes()
    {
        Config::set([
            'simple-payments.default-provider' => 'steeeve',
            'simple-payments.providers' => [
                'steeeve' => [
                    'class' => DummyProvider::class,
                ],
            ],
        ]);

        $repo = App::make(PaymentRepository::class);
        assert($repo instanceof PaymentRepository);

        $this->assertEquals(['steeeve'], $repo->availableProviders());
        $this->assertEquals('steeeve', $repo->name());
    }

    public function test_invalid_instantiating()
    {
        Config::set([
            'simple-payments.default-provider' => 'hotel',
            'simple-payments.providers' => [
                'hotel' => [],
            ],
        ]);

        $this->expectException(InvalidProviderException::class);
        $this->expectExceptionMessage('Roelofr\SimplePayments\PaymentProviders\HotelProvider');

        App::make(PaymentRepository::class);
    }

    public function test_disabled_providers_are_skipped()
    {
        Config::set([
            'simple-payments.default-provider' => 'hotel',
            'simple-payments.providers' => [
                'hotel' => [
                    'class' => DummyProvider::class,
                ],
                'lima' => [
                    'enabled' => false,
                ],
            ],
        ]);

        $repo = App::make(PaymentRepository::class);
        assert($repo instanceof PaymentRepository);

        $this->assertEquals(['hotel'], $repo->availableProviders());
        $this->assertEquals('hotel', $repo->name());
    }

    public function test_invalid_default_provider()
    {
        Config::set([
            'simple-payments.default-provider' => 'bravo',
            'simple-payments.providers' => [
                'alpha' => [
                    'class' => DummyProvider::class,
                ],
            ],
        ]);

        $this->expectException(ProviderNotFoundException::class);
        $this->expectExceptionMessage('default provider');

        App::make(PaymentRepository::class);
    }

    public function test_instantiating()
    {
        Config::set([
            'simple-payments.default-provider' => 'bravo',
            'simple-payments.providers' => [
                'alpha' => [
                    'class' => DummyProvider::class,
                ],
                'bravo' => [
                    'class' => DummyProvider::class,
                ],
            ],
        ]);

        $repo = App::make(PaymentRepository::class);
        assert($repo instanceof PaymentRepository);

        $alphaInvoice = $this->getInvoice(['payment_provider' => 'alpha']);
        $bravoInvoice = $this->getInvoice(['payment_provider' => 'bravo']);
        $charlieInvoice = $this->getInvoice(['payment_provider' => 'charlie']);

        $this->assertEquals('bravo', $repo->name());
        $this->assertInstanceOf(PaymentProvider::class, $repo->provider());

        $this->assertEquals('alpha', $repo->name($alphaInvoice));
        $this->assertInstanceOf(PaymentProvider::class, $repo->provider($alphaInvoice));

        $this->assertEquals('bravo', $repo->name($bravoInvoice));
        $this->assertInstanceOf(PaymentProvider::class, $repo->provider($bravoInvoice));

        $this->expectException(ProviderNotFoundException::class);

        $this->assertEquals('charlie', $repo->name($charlieInvoice));
        $repo->provider($charlieInvoice);
    }

    public function getInvoice(array $props, bool $create = true): Invoice
    {
        $invoice = new Invoice();

        $invoice->forceFill($props);

        if ($create) {
            $invoice->save();
        }

        return $invoice;
    }
}
