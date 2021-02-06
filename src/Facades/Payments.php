<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Facades;

use Illuminate\Support\Facades\Facade;
use Roelofr\SimplePayments\Contracts\PaymentRepository;

/**
 * @method static \Roelofr\SimplePayments\Contracts\PaymentProvider default()
 * @method static \Roelofr\SimplePayments\Contracts\PaymentProvider forInvoice(\Roelofr\SimplePayments\Models\Invoice $invoice)
 * @method static string getProviderName(?\Roelofr\SimplePayments\Models\Invoice $invoice)
 * @see \Roelofr\SimplePayments\Contracts\PaymentRepository
 */
class Payments extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PaymentRepository::class;
    }
}
