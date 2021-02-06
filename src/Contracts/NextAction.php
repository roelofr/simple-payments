<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

use JsonSerializable;

/**
 * Indicates this is a resolution for a payment, which can return text or a redirect.
 */
interface NextAction extends JsonSerializable
{
    /**
     * Returns the next URL.
     * @return null|string
     */
    public function getRedirectUrl(): ?string;

    /**
     * Get the message to display.
     * @return null|string
     */
    public function getMessage(): ?string;
}
