<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

use Money\Money;
use Roelofr\SimplePayments\Models\Invoice;

interface PaymentProvider
{
    /**
     * @param Money $value
     * @return array<PaymentMethod>
     */
    public function getPaymentMethods(Money $value): array;

    public function startPayment(Invoice $invoice, PaymentMethod $method): NextAction;

    public function update(Invoice $invoice): void;

    public function markPaid(Invoice $invoice): bool;

    public function markVoid(Invoice $invoice): bool;
}
