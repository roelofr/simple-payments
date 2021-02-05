<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models\PaymentMethods;

use Roelofr\SimplePayments\Contracts\PaymentMethod;

class AbstractMethod implements PaymentMethod
{
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}
