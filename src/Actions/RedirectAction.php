<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Actions;

use Roelofr\SimplePayments\Contracts\NextAction;

class RedirectAction implements NextAction
{
    protected string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->url;
    }

    public function getMessage(): ?string
    {
        return null;
    }

    public function jsonSerialize()
    {
        return [
            'type' => 'redirect',
            'url' => $this->url,
        ];
    }
}
