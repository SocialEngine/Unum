<?php namespace SocialEngine\Unum\Tests;

use PHPUnit_Framework_TestCase as TestCase;
use SocialEngine\Unum\Tests\Fixtures\BinaryEntity;
use SocialEngine\Unum\Tests\Fixtures\TestEntity;

class EntityTest extends TestCase
{
    public function test_create_entity()
    {
        $entity = new TestEntity();
        $entity['testProp'] = 'test';

        $this->assertContains('test', $entity->toArray(true));
        $entity->clean();
        $this->assertEmpty($entity->toArray(true));
    }

    public function test_create_entity_with_invalid_properties()
    {
        $this->setExpectedException('InvalidArgumentException');
        $entity = new TestEntity(['fake' => true]);
    }

    public function test_accessors()
    {
        $entity = new TestEntity([
            'testProp' => 'test1',
            'test_prop' => 'test2',
            'getMethodProp' => 'test3'
        ]);

        $this->assertSame($entity->testProp, 'test1');
        $this->assertSame($entity['testProp'], 'test1');
        $this->assertSame($entity->getMethodProp, 'test3 From Get');
    }

    public function test_accessing_unloaded_property()
    {
        $this->setExpectedException('InvalidArgumentException');

        $entity = new TestEntity(['test_prop' => 'test2']);

        $entity->testProp;
    }

    public function test_assignment()
    {
        $entity = new TestEntity();
        $entity['testProp'] = 'test';
        $entity->testProp = 'test';
        $entity->setMethodProp = 'test3';

        $this->assertSame($entity->setMethodProp, 'test3 From Set');
    }

    public function test_assignment_to_nonstring_property()
    {
        $this->setExpectedException('InvalidArgumentException');

        $entity = new TestEntity();
        $entity[true] = 1;
    }

    public function test_assignment_to_nonexistant_property()
    {
        $this->setExpectedException('InvalidArgumentException');

        $entity = new TestEntity();
        $entity->fake = 1;
    }

    public function test_isset()
    {
        $entity = new TestEntity(['testProp' => 'test1']);

        $this->assertTrue(isset($entity->testProp));
        $this->assertTrue(isset($entity['testProp']));
        $this->assertFalse(isset($entity->test_prop));
        $this->assertFalse(isset($entity['test_prop']));
    }

    public function test_empty_dirty_to_array()
    {
        $entity = new TestEntity();
        $this->assertInternalType('array', $entity->toArray(true));
    }

    public function test_has_changed()
    {
        $entity = new TestEntity(['testProp' => 'test1', 'test_prop' => 'test2']);
        // all changed should be false on clean entity
        $this->assertFalse($entity->hasChanged('testProp'));
        $this->assertFalse($entity->hasChanged('test_prop'));
        $this->assertFalse($entity->hasChanged(['testProp', 'test_prop']));
        $entity['testProp'] = 'test1_changed';
        $this->assertTrue($entity->hasChanged());
        $this->assertTrue($entity->hasChanged('testProp'));
        $this->assertFalse($entity->hasChanged('test_prop'));
        $this->assertFalse($entity->hasChanged(['testProp', 'test_prop']));
        $entity['test_prop'] = 'test2_changed';
        $this->assertTrue($entity->hasChanged(['testProp', 'test_prop']));
    }

    public function test_is_loaded()
    {
        $entity = new TestEntity();

        $this->assertFalse($entity->isLoaded());

        $entity->testProp = true;

        $this->assertTrue($entity->isLoaded());
    }

    public function test_unset_fails()
    {
        $this->setExpectedException('BadMethodCallException');

        $entity = new TestEntity(['testProp' => 'test1']);

        unset($entity->testProp);
    }

    public function test_unset_as_array_access_fails()
    {
        $this->setExpectedException('BadMethodCallException');

        $entity = new TestEntity(['testProp' => 'test1']);

        unset($entity['testProp']);
    }

    public function test_binary_trait()
    {
        $entity = new BinaryEntity(['binaryProp' => false]);

        $this->assertFalse($entity->changedAndEnabled('binaryProp'));

        $entity->binaryProp = true;

        $this->assertTrue($entity->changedAndEnabled('binaryProp'));

        $entity->binaryProp = 'cats';

        $this->assertFalse($entity->changedAndEnabled('binaryProp'));
    }
}
