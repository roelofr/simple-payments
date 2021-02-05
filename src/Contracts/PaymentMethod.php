<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

use JsonSerializable;
use Roelofr\SimplePayments\Models\PaymentCost;

interface PaymentMethod extends JsonSerializable
{
    public function getName(): string;

    public function getCode(): string;

    public function getCost(): PaymentCost;
}
