<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\PaymentMethods;

use Roelofr\SimplePayments\Models\PaymentCost;

class IdealMethod extends AbstractMethod
{
    protected array $banks = [];

    public function __construct(string $code, PaymentCost $paymentCost, string $name = 'iDEAL', array $banks = [])
    {
        parent::__construct($code, $paymentCost, $name);

        $this->addBanks($banks);
    }

    public function addBanks(array $banks): self
    {
        $this->banks = array_merge($this->banks, $banks);

        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'banks' => $this->banks,
        ]);
    }
}
