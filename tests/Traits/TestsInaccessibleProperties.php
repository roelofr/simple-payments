<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Tests\Traits;

use ReflectionClass;
use ReflectionException;

trait TestsInaccessibleProperties
{
    /**
     * Returns the value of a property on the object, ignoring visibility.
     * @param object $object
     * @param string $property
     * @return mixed
     * @throws ReflectionException
     */
    protected function getProperty(object $object, string $property)
    {
        $reflectionRepository = new ReflectionClass($object);

        $property = $reflectionRepository->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
