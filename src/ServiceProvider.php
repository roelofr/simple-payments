<?php

namespace Roelofr\SimplePayments;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Roelofr\SimplePayments\Commands\SimplePaymentsCommand;

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
