<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\PaymentMethods;

use Roelofr\SimplePayments\Models\PaymentCost;

class CreditCardMethod extends AbstractMethod
{
    public function __construct(string $code, PaymentCost $paymentCost, string $name = 'Credit Card')
    {
        parent::__construct($code, $paymentCost, $name);
    }
}
