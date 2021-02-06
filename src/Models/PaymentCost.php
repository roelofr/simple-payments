<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models;

use Money\Money;
use Roelofr\SimplePayments\Contracts\PaymentCost as PaymentCostContract;

class PaymentCost implements PaymentCostContract
{
    public Money $fixed;

    public float $flexible;

    public function __construct(Money $fixed, float $flexbile)
    {
        $this->fixed = $fixed;
        $this->flexbile = $flexbile;
    }

    public function getFixedPrice(): Money
    {
        return $this->fixed;
    }

    public function getFlexiblePrice(): float
    {
        return $this->flexible;
    }

    public function calculatePrice(Money $price): Money
    {
        $paymentCost = new Money(0, $price->getCurrency());

        $paymentCost->add($this->getFixedPrice());

        $paymentCost->add($price->multiply($this->getFlexiblePrice()));

        return $paymentCost;
    }

    public function jsonSerialize()
    {
        return [
            'fixed' => $this->fixed,
            'flexible' => $this->flexible,
        ];
    }
}
