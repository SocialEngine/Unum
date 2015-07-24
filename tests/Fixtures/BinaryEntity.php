<?php namespace SocialEngine\Unum\Tests\Fixtures;

use SocialEngine\Unum\Traits\BinaryHelper;

class BinaryEntity extends TestEntity
{
    use BinaryHelper;

    /**
     * @var bool
     */
    protected $binaryProp;
}
