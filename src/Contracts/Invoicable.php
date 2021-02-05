<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

interface Invoicable
{
    public function getInvoiceLines(): Collection;

    public function invoice(): BelongsTo;
}
