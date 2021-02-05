<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models;

use Money\Money;

class PaymentCost
{
    public Money $fixed;

    public float $flexible;

    public function __construct(Money $fixed, float $flexbile)
    {
        $this->fixed = $fixed;
        $this->flexbile = $flexbile;
    }

    /**
     * Determine the cost to pay this invoice.
     * @param Invoice|Invoicable $cost
     * @return void
     */
    public function compute(Invoice $cost)
    {
        # code...
    }
}
