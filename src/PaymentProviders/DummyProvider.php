<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\PaymentProvider;

use Illuminate\Support\Collection;
use Roelofr\SimplePayments\Contracts\Invoicable;
use Roelofr\SimplePayments\Contracts\PaymentProvider;
use Roelofr\SimplePayments\Models\Invoice;

class DummyProvider implements PaymentProvider
{
    public function getPaymentMethods($invoiceOrInvoicable): Collection
    {
        //
    }

    public function createInvoice(Invoicable $invoicable): Invoice
    {
        //
    }

    public function updateInvoice(Invoice $invoice): Invoice
    {
        //
    }

    public function markInvoicePaid(Invoice $invoice): Invoice
    {
        //
    }

    public function voidInvoice(Invoice $invoice): Invoice
    {
        //
    }
}
