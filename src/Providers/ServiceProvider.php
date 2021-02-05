<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Providers;

use Roelofr\SimplePayments\Commands\SimplePaymentsCommand;
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
            ->hasMigration('create_simple_payments_table')
            ->hasCommand(SimplePaymentsCommand::class);
    }
}
