<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Http\Controllers;

use Illuminate\Http\Response;
use Roelofr\SimplePayments\Models\Invoice;

class PaymentController extends Controller
{
    public function show(Invoice $invoice): Response
    {
    }

    public function start(Invoice $invoice, PaymentStartRequest $request): Response
    {
        # code...
    }

    public function message(Invoice $invoice, Request $request): Response
    {
        # code...
    }

    public function return(Invoice $invoice, Request $request): Response
    {
        # code...
    }

    public function complete(Invoice $invoice, Request $request): Response
    {
        # code...
    }
}
