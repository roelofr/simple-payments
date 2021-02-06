<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Tests\Fixtures\PaymentProviders;

use Money\Money;
use Roelofr\SimplePayments\Actions\MessageAction;
use Roelofr\SimplePayments\Contracts\NextAction;
use Roelofr\SimplePayments\Contracts\PaymentMethod;
use Roelofr\SimplePayments\Contracts\PaymentProvider;
use Roelofr\SimplePayments\Models\Invoice;

class TestProvider implements PaymentProvider
{
    private array $methods = [];

    public function addPaymentMethod(PaymentMethod $method): self
    {
        $this->methods[] = $method;

        return $this;
    }

    public function getPaymentMethods(Money $value): array
    {
        return $this->methods;
    }

    public function startPayment(Invoice $invoice, PaymentMethod $method): NextAction
    {
        return new MessageAction('Payment confirmed');
    }

    public function update(Invoice $invoice): void
    {
        $invoice->touch();
    }

    public function markPaid(Invoice $invoice): bool
    {
        return true;
    }

    public function markVoid(Invoice $invoice): bool
    {
        return true;
    }
}
