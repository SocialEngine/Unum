Unum: A simple entity implementation
===========

[![Build Status](https://travis-ci.org/SocialEngine/Unum.svg?branch=master)](https://travis-ci.org/SocialEngine/Unum)

## Usage

Create a class with protected attributes that extends `SocialEngine\Unum\Entity`:

```php
class MyEntity extends \SocialEngine\Unum\Entity
{
  protected $name;
  protected $email;
}
```

From there, new entities can be new-ed with an array of attributes:
 
```php
$entity = new MyEntity(['name' => 'Duke', 'email' => 'support@socialengine.com']);
```

### Accessing Attributes

Attributes can be accessed with either array or object syntax:

```php
$entity = new MyEntity(['name' => 'Duke', 'email' => 'support@socialengine.com']);
echo $entity->name; // Duke
echo $entity['email']; // support@socialengine.com
```

Accessing an attribute which has not been "loaded" with data will throw an `InvalidArgumentException`.

By defining a method of the form `get{AttributeName}`, the entity will use that method to return a property's value.

Note: snake_case attributes will be converted to PascalCase for methods, so `test_prop` becomes `getTestProp`

```php
class GetMethodEntity extends \SocialEngine\Unum\Entity
{
    protected $name;
    
    protected function getName()
    {
        return $name . ' From Method!';
    }
}

$entity = new GetMethodEntity(['name' => 'Hello']);
var_dump($entity->name); // string(18) "Hello From Method!"
```

### Assigning Attributes

Attributes can be assigned with either array or object syntax:

```php
$entity = new MyEntity();
$entity->name = 'My Name';
$entity['email'] = 'fake@example.com';
var_dump($entity->toArray());
/*
array(2) {
  'name' =>
  string(4) "Duke"
  'email' =>
  string(24) "support@socialengine.com"
}
*/
```

Assigning to an attribute which does not exist will throw an `InvalidArgumentException`.

By defining a method of the form `set{AttributeName}`, the entity will use that method to set a property's value.

Note: snake_case attributes will be converted to PascalCase for methods, so `test_prop` becomes `setTestProp`

```php
class SetMethodEntity extends \SocialEngine\Unum\Entity
{
    protected $name;
    
    protected function setName($value)
    {
        $this->name = $name . ' Set By Method!';
    }
}

$entity = new SetMethodEntity(['name' => 'Hello']);
var_dump($entity->name); // string(20) "Hello Set By Method!"
```

### Dirty Attributes

Assigning an entity's attribute a value which differs than its current value will mark that attribute as "dirty". To
retrieve an entity's dirty attributes, pass `true` as the `$dirty` flag to `toArray`

```php
$entity = new MyEntity();
$entity->name = 'My Name';
var_dump($entity->toArray(true));
/*
array(1) {
  'name' =>
  string(7) "My Name"
}
*/
```

Entities can be "cleaned" with the `clean` method, which will remove the "dirty" mark from all attributes.


```php
$entity = new MyEntity();
$entity->name = 'My Name';
$entity->email = 'whatever';
$entity->clean();
var_dump($entity->toArray(true));
/*
array(0) {
}
*/
```

## Code Style

Please follow the following guides and code standards:

### PHP
* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)

### Git

* [Agis Git Style Guide](https://github.com/agis-/git-style-guide)


#### Addendum and Clarifications

* `namespace` should be on the same line as opening php tag: `<?php namespace SocialEngine\Amazing`
* Property names should be camelCase
* Test names should use underscores, not camelCase. e.g.: `test_cats_love_catnip`

## License

[MIT](./LICENSE).
