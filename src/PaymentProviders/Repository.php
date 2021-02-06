<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\PaymentProviders;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Roelofr\SimplePayments\Contracts\PaymentProvider;
use Roelofr\SimplePayments\Contracts\PaymentRepository;
use Roelofr\SimplePayments\Exceptions\Logic\InvalidProviderException;
use Roelofr\SimplePayments\Exceptions\Runtime\ProviderNotFoundException;
use Roelofr\SimplePayments\Models\Invoice;

final class Repository implements PaymentRepository
{
    /**
     * @var string
     * @psalm-type string-class<PaymentProvider>
     */
    private string $default;

    /**
     * @var array<string>
     * @psalm-type array<string,string-class<PaymentProvider>>
     */
    private array $providers;

    /**
     * @var array<PaymentProvider>
     * @psalm-type array<string,PaymentProvider>
     */
    private array $instances = [];

    /**
     * @throws InvalidProviderException if one of the supplied providers is invalid.
     * @throws ProviderNotFoundException if the default provider is not available.
     */
    public function __construct()
    {
        $providers = Config::get('simple-payments.providers');
        $defaultProvider = Config::get('simple-payments.default-provider');

        $this->assignProviders($providers);

        $this->setDefaultProvider($defaultProvider);
    }

    /**
     * Return a list of the name of available providers.
     * @return array<string>
     */
    public function availableProviders(): array
    {
        return array_keys($this->providers);
    }

    /**
     * Return the name of the provider for the invoice.
     * @return string
     */
    public function name(?Invoice $invoice = null): string
    {
        return optional($invoice)->payment_provider ?? $this->default;
    }

    /**
     * Get the provider for the given invoice
     * @param Invoice $invoice
     * @return PaymentProvider
     */
    public function provider(?Invoice $invoice = null): PaymentProvider
    {
        return $this->getProvider($this->name($invoice));
    }

    /**
     * Get the provider, throws errors if they're not found.
     * @param string $provider
     * @return PaymentProvider
     * @throws ProviderNotFoundException
     */
    protected function getProvider(string $provider): PaymentProvider
    {
        if (isset($this->instances[$provider])) {
            return $this->instances[$provider];
        }

        if (! isset($this->providers[$provider])) {
            throw new ProviderNotFoundException(sprintf(
                'Cannot instantiate unregistered provider [%s], available providers are %s.',
                $provider,
                collect($this->providers)
                    ->keys()
                    ->map(fn ($value) => "[{$value}]")
                    ->join(', ', ' or ')
            ));
        }

        $this->instances[$provider] = App::make(
            $this->providers[$provider]['class'],
            [
                'config' => $this->providers[$provider]['config'],
                'name' => $provider,
            ]
        );

        return $this->instances[$provider];
    }

    /**
     * Determines the right class name in order of preferencem throws an exception if none match.
     * @param string $provider
     * @param null|string $defaultClass
     * @return string
     * @throws InvalidProviderException
     */
    private function determineProviderClass(string $provider, ?string $defaultClass): string
    {
        $studlyProvider = Str::studly($provider);

        $options = collect([
            $defaultClass,

            // Shipped
            sprintf('Roelofr\SimplePayments\PaymentProviders\%sProvider', $studlyProvider),

            // Third-party
            sprintf('Roelofr\SimplePayments\%1$s\%1$sProvider', $studlyProvider),
            sprintf('Roelofr\SimplePayments\%s\PaymentProvider', $studlyProvider),
        ])->filter();

        foreach ($options as $providerClass) {
            // Ensure it's valid
            if (! class_exists($providerClass) || ! is_a($providerClass, PaymentProvider::class, true)) {
                continue;
            }

            return $providerClass;
        }

        throw new InvalidProviderException(sprintf(
            'Cannot determine proper provider class for %s. Tried using %s, but none seem valid.',
            $provider,
            $options
                ->map(fn ($value) => "[{$value}]")
                ->join(', ', ' and ')
        ));
    }

    /**
     * Validates the providers in $providers, and
     * @param array $providers
     * @return void
     * @throws InvalidProviderException
     */
    private function assignProviders(array $providers): void
    {
        $safeProviders = [];

        foreach ($providers as $name => $config) {
            // Skip disabled providers
            if (($config['enabled'] ?? true) === false) {
                continue;
            }

            // Determine class
            $providerClass = $this->determineProviderClass($name, $config['class'] ?? null);

            // Assign and save the config too
            $safeProviders[$name] = [
                'class' => $providerClass,
                'config' => Arr::except($config, ['class']),
            ];
        }

        $this->providers = $safeProviders;
    }

    /**
     * Sets the default provider safely.
     * @param string $defaultProvider
     * @return void
     * @throws ProviderNotFoundException
     */
    private function setDefaultProvider(string $defaultProvider): void
    {
        if (! isset($this->providers[$defaultProvider])) {
            throw new ProviderNotFoundException(sprintf(
                'The chosen default provider [%s] is not registered. Please chose one of %s',
                $defaultProvider,
                collect($this->providers)
                    ->keys()
                    ->map(fn ($value) => "[{$value}]")
                    ->join(', ', ' or ')
            ));
        }

        $this->default = $defaultProvider;
    }
}
