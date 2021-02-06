<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Actions;

use Roelofr\SimplePayments\Contracts\NextAction;

class MessageAction implements NextAction
{
    protected string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function jsonSerialize()
    {
        return [
            'type' => 'message',
            'message' => $this->message,
        ];
    }
}
