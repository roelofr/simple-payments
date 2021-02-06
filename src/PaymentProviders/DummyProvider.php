<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\PaymentProviders;

use Money\Money;
use Roelofr\SimplePayments\Contracts\NextAction;
use Roelofr\SimplePayments\Contracts\PaymentMethod;
use Roelofr\SimplePayments\Contracts\PaymentProvider;
use Roelofr\SimplePayments\Models\Invoice;

class DummyProvider implements PaymentProvider
{
    public function getPaymentMethods(Money $price): array
    {
        //
    }

    public function startPayment(Invoice $invoice, PaymentMethod $method): NextAction
    {
        //
    }

    public function update(Invoice $invoice): void
    {
        //
    }

    public function markPaid(Invoice $invoice): bool
    {
        return false;
    }

    public function markVoid(Invoice $invoice): bool
    {
        return false;
    }
}
