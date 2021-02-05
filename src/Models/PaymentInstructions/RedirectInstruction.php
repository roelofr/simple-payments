<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models\PaymentInstructions;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Roelofr\SimplePayments\Contracts\PaymentInstruction;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class RedirectInstruction implements PaymentInstruction
{
    protected string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Return a redirect with safe headers.
     *
     * @param Request $request
     * @return HttpFoundationResponse
     */
    public function toResponse($request)
    {
        return Response::redirectTo($this->url, HttpResponse::HTTP_SEE_OTHER, [
            'Referrer-Policy' => 'strict-origin',
        ]);
    }

    /**
     * Print the URL to go to a target.
     * @return string
     */
    public function __toString(): string
    {
        return "Continue to <{$this->url}>...";
    }
}
