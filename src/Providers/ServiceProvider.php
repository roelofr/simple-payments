<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Providers;

use Roelofr\SimplePayments\Commands\SimplePaymentsCommand;
use Roelofr\SimplePayments\Contracts\PaymentRepository;
use Roelofr\SimplePayments\Facades\Util;
use Roelofr\SimplePayments\PaymentProviders\Repository;
use Roelofr\SimplePayments\Services\Utilities;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('simple-payments')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(SimplePaymentsCommand::class);
    }

    /**
     * Bind stuff to the service provider.
     * @return void
     */
    public function packageRegistered()
    {
        $this->app->singleton(PaymentRepository::class, Repository::class);
        $this->app->singleton(Util::class, Utilities::class);

        $this->loadMigrationsFrom($this->package->basePath('/../database/migrations'));
    }

    /**
     * We're deviating from the Spatie standard, so let's just override this
     * function :)
     */
    protected function getPackageBaseDir(): string
    {
        return realpath(dirname(__DIR__));
    }
}
