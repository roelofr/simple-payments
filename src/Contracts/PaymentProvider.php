<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

use Illuminate\Support\Collection;
use Roelofr\SimplePayments\Exceptions\DuplicateInvoiceException;
use Roelofr\SimplePayments\Exceptions\InvoiceStateException;
use Roelofr\SimplePayments\Models\Invoice;

interface PaymentProvider
{
    /**
     *
     * @param Invoice|Invoicable $invoiceOrInvoicable
     * @return Roelofr\SimplePayments\Contracts\Collection
     */
    public function getPaymentMethods($invoiceOrInvoicable): Collection;

    /**
     * Creates an invoice for the given invoicable model, auto-assigns the `invoice` property.
     * @param Invoicable $invoicable
     * @return Invoice
     * @throws DuplicateInvoiceException if $invoicable already has a pending invoice.
     */
    public function createInvoice(Invoicable $invoicable): Invoice;

    /**
     * Triggers an invoice update, which will result in an API call.
     * @param Invoice $invoice
     * @return Invoice
     * @throws InvoiceNotFoundException if $invoice is not known to this provider
     */
    public function updateInvoice(Invoice $invoice): Invoice;

    /**
     * Marks the given invoice as paid.
     * @param Invoice $invoice
     * @return Invoice
     * @throws InvoiceStateException If $invoice cannot be marked as paid right now.
     * @throws InvoiceNotFoundException if $invoice is not known to this provider
     */
    public function markInvoicePaid(Invoice $invoice): Invoice;

    /**
     * Marks an invoice as voided, requiring no payment anymore.
     * @param Invoice $invoice
     * @return Invoice
     * @throws InvoiceStateException If $invoice cannot be marked as void right now.
     * @throws InvoiceNotFoundException if $invoice is not known to this provider
     */
    public function voidInvoice(Invoice $invoice): Invoice;
}
