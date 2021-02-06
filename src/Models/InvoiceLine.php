<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models;

use DomainException;
use JsonSerializable;
use Money\Money;
use Roelofr\SimplePayments\Facades\Util;
use Throwable;

class InvoiceLine implements JsonSerializable
{
    public string $name;

    public ?string $description = null;

    public ?Money $price = null;

    public ?int $count = null;

    private bool $locked = false;

    public function setName(string $name): self
    {
        $this->assertNotLocked();

        $this->name = $name;

        return $this;
    }

    /**
     * The total price of the line, individual price is computed from this.
     * @param null|Money $price
     * @return InvoiceLine
     * @throws Throwable
     */
    public function setPrice(?Money $price): self
    {
        $this->assertNotLocked();

        $this->price = $price;

        return $this;
    }

    /**
     * The number of items, if you want to specify this.
     * @param null|int $count
     * @return InvoiceLine
     * @throws Throwable
     */
    public function setCount(?int $count): self
    {
        $this->assertNotLocked();

        $this->count = $count;

        return $this;
    }

    /**
     * The description of the item, if any
     * @param null|string $description
     * @return InvoiceLine
     * @throws Throwable
     */
    public function setDescription(?string $description): self
    {
        $this->assertNotLocked();

        $this->description = $description;

        return $this;
    }

    /**
     * Mark the resource as complete, preventing modification.
     * @return InvoiceLine
     */
    public function lock(): self
    {
        $this->locked = true;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'count' => $this->count,
        ];
    }

    final protected function assertNotLocked(): void
    {
        throw_if(
            $this->locked,
            DomainException::class,
            'Attempted to modify a locked invoice line, but that\'s not allowed'
        );
    }

    public function __toString()
    {
        if ($this->count === null) {
            sprintf(
                '%s (%s) for %s',
                $this->name,
                $this->description,
                $this->price ? Util::moneyToString($this->price) : null
            );
        }

        return sprintf(
            '%d units of %s (%s) for %s',
            $this->count,
            $this->name,
            $this->description,
            $this->price ? Util::moneyToString($this->price) : null
        );
    }
}
