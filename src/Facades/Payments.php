<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Facades;

use Illuminate\Support\Facades\Facade;
use Roelofr\SimplePayments\Payments as PaymentsInstance;

/**
 * @see \Roelofr\SimplePayments\Payments
 */
class Payments extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PaymentsInstance::class;
    }
}
