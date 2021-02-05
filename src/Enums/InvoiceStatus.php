<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Enums;

use MyCLabs\Enum\Enum;

class InvoiceStatus extends Enum
{
    public const DRAFT = 'DRAFT';

    public const OPEN = 'OPEN';

    public const PAID = 'PAID';

    public const VOID = 'VOID';

    public const REJECTED = 'REJECTED';
}
