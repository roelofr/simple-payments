<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

use JsonSerializable;
use Money\Money;

interface PaymentCost extends JsonSerializable
{
    public function getFixedPrice(): Money;

    public function getFlexiblePrice(): float;

    public function calculatePrice(Money $price): Money;
}
