<?php namespace SocialEngine\Unum\Traits;

use SocialEngine\Unum\Contracts\Entity;

trait PropertyAsserts
{
    /**
     * Asserts that a property of Entity is loaded.
     *
     * @param  Entity   $entity
     * @param  string   $property
     * @param  string   $message
     */
    public function assertPropertyLoaded(Entity $entity, $property, $message = '')
    {
        if (empty($message)) {
            $message = 'Failed asserting property loaded: %1$s of %2$s.';
        }
        $message = sprintf($message, $property, get_class($entity));
        $this->assertTrue(isset($entity->{$property}), $message);
    }

    /**
     * Asserts that properties of Entity are loaded.
     *
     * @param  Entity   $entity
     * @param  array    $properties
     * @param  string   $message
     */
    public function assertPropertiesLoaded(Entity $entity, array $properties, $message = '')
    {
        array_walk($properties, function ($property) use ($entity, $message) {
            $this->assertPropertyLoaded($entity, $property, $message);
        });
    }

    /**
     * Asserts that a property of Entity has the same value.
     *
     * @param  Entity   $entity
     * @param  string   $property
     * @param  mixed    $value
     * @param  string   $message
     */
    public function assertProperty(Entity $entity, $property, $value = null, $message = '')
    {
        $this->assertPropertyLoaded($entity, $property);
        if (empty($message)) {
            $message = 'Failed asserting the value %1$s of %2$s.';
        }
        $message = sprintf($message, $value, $property);
        $this->assertSame($value, $entity->{$property}, $message);
    }

    /**
     * Asserts that properties of Entity have the same value.
     *
     * @param  Entity   $entity
     * @param  array    $properties
     * @param  string   $message
     */
    public function assertProperties(Entity $entity, array $properties, $message = '')
    {
        array_walk($properties, function ($value, $property) use ($entity, $message) {
            $this->assertProperty($entity, $property, $value, $message);
        });
    }

    /**
     * Asserts that a condition is true.
     *
     * @param  boolean  $condition
     * @param  string   $message
     */
    abstract public function assertTrue($condition, $message = '');

    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    abstract public function assertSame($expected, $actual, $message = '');
}
