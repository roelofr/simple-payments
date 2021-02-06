<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;
use Money\Currency;
use Money\Money;
use RuntimeException;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        if ($value === null) {
            return null;
        }

        if (! is_string($value)) {
            throw new RuntimeException("Failed to cast attribute [{$key}] to a Money object.");
        }

        $value = json_decode($value, true, 8, JSON_THROW_ON_ERROR);

        if (! is_array($value) || ! Arr::has($value, ['amount', 'currency'])) {
            throw new RuntimeException("Failed to cast attribute [{$key}] to a Money object.");
        }

        return new Money(
            Arr::get($value, 'amount'),
            new Currency(Arr::get($value, 'currency')),
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return json_encode($value);
    }
}
