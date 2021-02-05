<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models\PaymentInstructions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Roelofr\SimplePayments\Contracts\PaymentInstruction;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class TextInstruction implements PaymentInstruction
{
    protected string $message;

    public function __construct(string $message)
    {
        $this->message = $url;
    }

    /**
     * Return a redirect with safe headers.
     *
     * @param Request $request
     * @return HttpFoundationResponse
     */
    public function toResponse($request)
    {
        Session::put('payments.text-instruction', $this->message);

        return Response::redirectToRoute();
    }

    /**
     * Print the URL to go to a target.
     * @return string
     */
    public function __toString(): string
    {
        return "Instructions from provider:\n\n{$this->message}";
    }
}
