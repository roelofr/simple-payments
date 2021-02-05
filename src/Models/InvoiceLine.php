<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use JsonSerializable;
use Money\Money;

class InvoiceLine implements JsonSerializable
{
    public string $name;

    public ?string $description = null;

    public ?Money $price = null;

    public int $count = 1;

    public static function fromJson(array $data) :self
    {
        // Check data
        if (!Arr::has($data, ['name', 'description', 'price', 'count'])) {
            throw new InvalidArgumentException("Data is missing one or more required keys");
        }

        // Map result as far as possible
        $result = (new self())
            ->setName(Arr::get($data, 'name'))
            ->setCount(Arr::get($data, 'count'))
            ->setDescription(Arr::get($data, 'description'));

        // Check price
        if (!Arr::has($data, ['price.amount', 'price.currency'])) {
            return $result;
        }

        // Add price and return
        return $result->setPrice(new Money(
            Arr::get($data, 'price.amount'),
            Arr::get($data, 'price.currency'),
        ));

    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setPrice(?Money $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
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

    public function __toString()
    {
        return sprintf(
            '%d units of %s (%s) for %s',
            $this->count,
            $this->name,
            $this->description,
            $this->price ? $this->price->,
        );
    }
}
