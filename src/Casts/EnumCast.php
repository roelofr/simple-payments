<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use LogicException;
use MyCLabs\Enum\Enum;

final class EnumCast implements CastsAttributes
{
    private string $enumClass;

    public function __construct(string $enumClass)
    {
        if (! is_a($enumClass, Enum::class, true)) {
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
        // Null is always valid, and so is a valid instance.
        if ($value === null || is_a($value, $this->enumClass)) {
            return optional($value)->getValue();
        }

        // Allow valid enums if they're not an enum instance
        if ($this->enumClass::isValid($value)) {
            return $value;
        }

        // oh no
        throw new InvalidArgumentException(sprintf(
            'Expected value of %s to be of type %s (%s), got %s instead',
            $key,
            class_basename($this->enumClass),
            $this->enumClass,
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }
}
