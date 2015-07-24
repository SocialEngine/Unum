<?php namespace SocialEngine\Unum\Tests\Fixtures;

use SocialEngine\Unum\Entity;

/**
 * @property $testProp
 * Class TestEntity
 */
class TestEntity extends Entity
{
    protected $testProp;
    protected $getMethodProp;
    protected $setMethodProp;

    // While having properties with underscores is against our style guide, we want to make sure they work.
    // @codingStandardsIgnoreStart
    protected $test_prop;
    // @codingStandardsIgnoreEnd

    protected function getGetMethodProp()
    {
        return $this->getMethodProp . ' From Get';
    }

    protected function setSetMethodProp($value)
    {
        $this->setMethodProp = $value . ' From Set';
    }
}
