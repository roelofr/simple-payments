<?php

namespace Roelofr\SimplePayments\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use LogicException;
use MyCLabs\Enum\Enum;

final class EnumCast implements CastsAttributes
{
    private string $enumClass;

    public function __construct(string $enumClass)
    {
        if (!is_a($enumClass, Enum::class, true)) {
            throw new LogicException("Invalid enum target [{$enumClass}] specified.");
        }

        $this->enumClass = $enumClass;
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
        if ($value === null) {
            return null;
        }

        $enumClass = $this->enumClass;

        return new $enumClass($value);
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
        return $value;
    }
}
