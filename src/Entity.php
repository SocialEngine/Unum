<?php namespace SocialEngine\Unum;

use ArrayAccess;
use InvalidArgumentException;
use BadMethodCallException;

class Entity implements ArrayAccess, Contracts\Entity
{
    /**
     * Properties that cannot be set
     * @var array
     */
    protected $propertyBlacklist = ['propertyBlacklist', 'propertyLoaded', 'propertyDirty'];

    /**
     * Properties that have been loaded
     * @var array
     */
    protected $propertyLoaded = [];

    /**
     * Properties that are dirty (modified)
     * @var array
     */
    protected $propertyDirty = [];

    /**
     * Constructor
     *
     * @param  array $data
     * @throws InvalidArgumentException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->fromArray($data);
            $this->clean();
        }
    }

    /**
     * Get Property
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getProp($key);
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function __set($key, $value)
    {
        $this->setProp($key, $value);
        return $this;
    }

    public function __isset($key)
    {
        return in_array($key, $this->propertyLoaded);
    }

    public function __unset($key)
    {
        return $this->offsetUnset($key);
    }

    /**
     * Clean Dirty Properties
     *
     * @return self
     */
    public function clean()
    {
        $this->propertyDirty = [];
        return $this;
    }

    /**
     * Has Entity/Key changed
     *
     * @param null $key
     * @return bool
     */
    public function hasChanged($key = null)
    {
        if ($key === null) {
            return (bool)count($this->propertyDirty);
        } elseif (is_array($key)) {
            return array_reduce($key, function ($carry, $key) {
                return $this->hasChanged($key) === $carry && $carry === true;
            }, true);
        } else {
            return in_array($key, $this->propertyDirty);
        }
    }

    /**
     * Does the Entity have any loaded values?
     *
     * @return bool
     */
    public function isLoaded()
    {
        return (bool)count($this->propertyLoaded);
    }

    /**
     * Create a new Entity from an Array
     *
     * @param array $data
     * @return self
     * @throws InvalidArgumentException
     */
    public function fromArray(array $data)
    {
        foreach ($data as $k => $v) {
            if (!property_exists($this, $k) || in_array($k, $this->propertyBlacklist)) {
                throw new InvalidArgumentException(sprintf(
                    '$data key: "%s" was not listed in the allowed properties',
                    $k
                ));
            }
            $this->setProp($k, $v);
        }

        return $this;
    }

    /**
     * To Array
     *
     * @param  bool $dirty fetch dirty elements
     * @return array
     */
    public function toArray($dirty = false)
    {
        $data = [];
        $elements = ($dirty) ? $this->propertyDirty : $this->propertyLoaded;
        foreach ($elements as $key) {
            $data[$key] = $this->getProp($key);
        }
        return $data;
    }

    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->getProp($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->setProp($offset, $value);
    }

    /**
     * This method need to be defined to conform to ArrayAccess interface
     *
     * Developer's Note: We have thought hard about what calling 'unset' on a property means for an entity, and have
     * concluded that it does not have an obvious answer. Because of the ambiguity and possible confusion unset would
     * create, we have opted to not implement it.
     *
     * @param mixed $offset
     * @throws BadMethodCallException
     */
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException(
            "Calling unset on an entity's properties is not supported. See documentation of offsetUnset for more info."
        );
    }

    /**
     * @param $key
     * @param $value
     */
    protected function setProp($key, $value)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException('$key must be a string');
        }
        if (!property_exists($this, $key) || in_array($key, $this->propertyBlacklist)) {
            throw new InvalidArgumentException(sprintf(
                'Key: "%s" is not an allowed property',
                $key
            ));
        }

        $this->propertyLoaded[] = $key;

        if ($this->getProp($key) !== $value) {
            $methodName = $this->normalizeAccessor($key);
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            } else {
                $this->$key = $value;
            }
            $this->propertyDirty[] = $key;
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function getProp($key)
    {
        if (!in_array($key, $this->propertyLoaded)) {
            throw new InvalidArgumentException(sprintf(
                'Key: "%s" was not loaded',
                $key
            ));
        }
        $methodName = $this->normalizeAccessor($key, false);

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } else {
            return $this->$key;
        }
    }

    /**
     * @param $key
     * @param bool $set
     * @return string
     */
    protected function normalizeAccessor($key, $set = true)
    {
        $methodName = str_replace('_', ' ', $key);
        $methodName = ucwords($methodName);
        $methodName = str_replace(' ', '', $methodName);
        $prefix = ($set) ? 'set' : 'get';
        return $prefix . $methodName;
    }
}
