<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Roelofr\SimplePayments\Contracts\Invoicable;
use Roelofr\SimplePayments\Facades\Util;
use Roelofr\SimplePayments\Models\InvoiceLine;

class Order extends Model implements Invoicable
{
    public function addProduct(string $name, int $price): self
    {
        $this->products[] = [$name, $price];

        return $this;
    }

    public function getInvoiceLines(): array
    {
        $lines = [];

        foreach ($this->products as $product) {
            $lines[] = (new InvoiceLine())
                ->setName($product[0])
                ->setPrice(Util::getMonetaryValue($product[1]));
        }

        return $lines;
    }
}
