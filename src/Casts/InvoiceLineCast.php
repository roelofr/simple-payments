<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Money\Currency;
use Money\Money;
use Roelofr\SimplePayments\Models\InvoiceLine;

class InvoiceLineCast implements CastsAttributes
{
    public function arrayToInvoiceLine(array $data): InvoiceLine
    {
        // Check data
        if (! Arr::has($data, ['name', 'description', 'price', 'count'])) {
            throw new InvalidArgumentException("Data is missing one or more required keys");
        }

        // Map result as far as possible
        $result = (new InvoiceLine())
            ->setName(Arr::get($data, 'name'))
            ->setCount(Arr::get($data, 'count'))
            ->setDescription(Arr::get($data, 'description'));

        // Add price
        if (Arr::has($data, ['price.amount', 'price.currency'])) {
            $result->setPrice(new Money(
                Arr::get($data, 'price.amount'),
                new Currency(Arr::get($data, 'price.currency')),
            ));
        }

        // Lock resource and return it
        return $result->lock();
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        // Decode from JSON
        if (is_string($value)) {
            $value = json_decode($value, true, 64, JSON_THROW_ON_ERROR);
        }

        // Don't allow null
        $value ??= [];

        // Prep a collection
        $result = [];

        // Iterate
        foreach ($value as $rowData) {
            $result[] = $this->arrayToInvoiceLine($rowData);
        }

        // Return result
        return $result;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        // Always assume some value
        $value ??= [];

        // Must be a collection
        if (! is_iterable($value)) {
            throw new InvalidArgumentException("Values of {$key} are not castable to invoice lines");
        }

        // Must be exclusively filled with InvoiceLine models
        foreach ($value as $items) {
            if (! $items instanceof InvoiceLine) {
                throw new InvalidArgumentException("Values of {$key} does not exclusively contain InvoiceLine models.");
            }
        }

        // Encode as JSON
        return json_encode($value, JSON_THROW_ON_ERROR, 64);
    }
}
