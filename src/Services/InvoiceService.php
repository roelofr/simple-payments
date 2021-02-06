<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Services;

use InvalidArgumentException;
use Roelofr\SimplePayments\Contracts\Invoicable;
use Roelofr\SimplePayments\Facades\Payments;
use Roelofr\SimplePayments\Facades\Util;
use Roelofr\SimplePayments\Models\Invoice;
use Roelofr\SimplePayments\Models\InvoiceLine;

class InvoiceService
{
    public function createInvoice(Invoicable $subject): Invoice
    {
        // Get invoice lines
        $lockedLines = [];

        // Validate each line, and ensure it's locked
        foreach ($subject->getInvoiceLines() as $index => $line) {
            if (! $line instanceof InvoiceLine) {
                throw new InvalidArgumentException(sprintf(
                    'Expected all invoice lines to be instances of [%s], got [%s] at index [%s].',
                    InvoiceLine::class,
                    is_object($line) ? get_class($line) : gettype($line),
                    $index
                ));
            }

            $lockedLines[] = (clone $line)->lock();
        }

        // Prep the invoice
        $invoice = new Invoice();

        $invoice->subject()->associate($subject);

        // Assign products and compute total price
        $invoice->products = $subject->getInvoiceLines();
        $invoice->price = Util::determineValue($subject);

        // Determine provider
        $provider = Payments::forInvoice($invoice);
        $providerName = Payments::getProviderName($invoice);

        $invoice->payment_method = $providerName;
        $invoice->payment_method_price = Util::determineCheapestRate($provider, $invoice->price);

        return $invoice;
    }
}
