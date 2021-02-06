<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

interface Invoicable
{
    /**
     * Returns the lines on the invoice.
     * @return array<\Roelofr\SimplePayments\Models\InvoiceLine>
     */
    public function getInvoiceLines(): array;
}
