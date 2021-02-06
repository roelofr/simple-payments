<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

use Roelofr\SimplePayments\Models\Invoice;

interface PaymentRepository
{
    /**
     * Return a list of the name of available providers.
     * @return array<string>
     */
    public function availableProviders(): array;

    /**
     * Get the provider for the given invoice
     * @param Invoice $invoice
     * @return PaymentProvider
     */
    public function provider(?Invoice $invoice = null): PaymentProvider;

    /**
     * Return the name of the provider for the invoice.
     * @return string
     */
    public function name(?Invoice $invoice = null): string;
}
