<?php

namespace Roelofr\SimplePayments\Casts;

use InvalidArgument;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Roelofr\SimplePayments\Models\InvoiceLine;

class InvoiceLineCollectionCast implements CastsAttributes
{
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
        $result = new Collection();

        // Iterate
        foreach ($value as $row) {
            $result->push(InvoiceLine::fromJson($row));
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
        $value ??= new Collection();

        // Must be a collection
        if (!$value instanceof Collection) {
            throw new InvalidArgumentException("Values of {$key} are not castable to invoice lines");
        }

        // Must be exclusively filled with InvoiceLine models
        if ($value->filter(fn ($row) => $row instanceof InvoiceLine)->count() !== $value->count()) {
            throw new InvalidArgumentException("Values of {$key} does not exclusively contain InvoiceLine models");
        }

        // Encode as JSON
        return json_encode($value, JSON_THROW_ON_ERROR, 64);
    }
}
