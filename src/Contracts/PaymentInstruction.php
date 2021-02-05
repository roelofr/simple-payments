<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

use Illuminate\Contracts\Support\Responsable;

/**
 * Indicates this is a resolution for a payment, which can return text or a redirect.
 */
interface PaymentInstruction extends Responsable
{
    /**
     * Require a stringable response.
     *
     * @return string
     */
    public function __toString(): string;
}
