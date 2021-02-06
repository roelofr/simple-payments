<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\PaymentMethods;

use Roelofr\SimplePayments\Contracts\PaymentMethod;
use Roelofr\SimplePayments\Models\PaymentCost;

abstract class AbstractMethod implements PaymentMethod
{
    protected string $name;

    protected string $code;

    protected PaymentCost $paymentCost;

    public function __construct(string $code, PaymentCost $paymentCost, string $name)
    {
        $this->name = $name;
        $this->code = $code;
        $this->paymentCost = $paymentCost;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCost(): PaymentCost
    {
        return $this->paymentCost;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}
