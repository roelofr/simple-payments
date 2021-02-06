<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Tests\Fixtures\Providers;

use Illuminate\Support\ServiceProvider;
use Roelofr\SimplePayments\Tests\Fixtures\PaymentProviders\TestProvider;

class TestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

        $this->app->singleton(TestProvider::class);
    }
}
