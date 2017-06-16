# Moka - Shorthand for Creating Mock Objects

[![Packagist](https://img.shields.io/packagist/l/facile-it/moka.svg)](/LICENSE)
[![GitHub release](https://img.shields.io/github/release/facile-it/moka.svg)](https://packagist.org/packages/facile-it/moka)
[![Travis](https://img.shields.io/travis/facile-it/moka/master.svg)](https://travis-ci.org/facile-it/moka/branches)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/facile-it/moka.svg)](https://scrutinizer-ci.com/g/facile-it/moka/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/facile-it/moka.svg)](https://scrutinizer-ci.com/g/facile-it/moka/?branch=master)
[![Packagist](https://img.shields.io/packagist/dt/facile-it/moka.svg)](https://packagist.org/packages/facile-it/moka)

Tired of spending most of your testing time mocking objects like there's no tomorrow? **Yes.**  
**Moka** provides you with three simple methods to reduce your effort on such a tedious task.

## Installation

You can install the package via composer:

```bash
composer require --dev facile-it/moka
```

## Usage

To use **Moka** in your tests simply `use` the `MokaTrait` and run `Moka::clean()` before every test. A simple interface will let you create *mock* objects, *serve* them easily, and decorate them with *stub* methods with a fluent interface.

A complete example follows:

```php
<?php

namespace Foo\Tests;

use Moka\Moka;
use Moka\Traits\MokaTrait;

class FooTest extends AnyTestCase
{
    use MokaTrait;
    
    private $foo;
    
    public function setUp()
    {
        Moka::clean();
        
        // The subject of the test.
        $this->foo = new Foo(
            $this->mock(BarInterface::class)->stub([
                // Method name => return value.
                'method1' => $this->mock(AcmeInterface::class)->serve(),
                'method2' => true // Any return value.
            ])->serve()
        );
    }
    
    ...
}
```

Being such a simple project, **Moka** can be integrated in an already existing test suite with no effort.

## Reference

### `mock(string $fqcn, string $alias = null): MockProxy`

Creates (if not existing already) a proxy containing a PHPUnit mock object for the class identified by `$fqcn` and optionally assigns an `$alias` to it.

```php
$mock1 = $this->mock(FooInterface::class)->serve(); // Creates the mock for FooInterface.
$mock2 = $this->mock(FooInterface::class)->serve(); // Gets the mock previously created.

var_dump($mock1 === $mock2);
// bool(true)
```

The `$alias` allows you to create different instances of the same `$fqcn`; you will refer to them by the `$alias` from now on.

```php
$this->mock(FooInterface::class, 'foo1')->serve(); // Creates a mock for FooInterface.
$this->mock(FooInterface::class, 'foo2')->serve(); // Gets a different mock.

var_dump($this->mock('foo1') === $this->mock('foo2'));
// bool(false)
```

### `stub(array $methodsWithValues): self`

Accepts an array of method stubs with format `[$methodName => $methodValue]`, where `$methodName` **must** be a string and `$methodValue` can be of any type, including another mock object or an exception instance.

```php
$actualMock = $this->mock(BarInterface::class)->stub([
    'isValid' => true,
    // Remember to use serve() to pass the actual mock.
    'getMock' => $this->mock(AcmeInterface::class)->serve(),
    'throwException' => new \Exception()
])->serve();

var_dump($actualMock->isValid());
// bool(true)
```

**Notice:** the stub is valid for **any** invocation of the method.  
If you need more granular control over invocation strategies, see `serve()`.

### `serve(): MockObject // PHPUnit mock object class`

Returns the actual mock object unwrapped from the proxy.

```php
$this->mock(BarInterface::class)->serve()
    ->expects($this->at(0))
    ->method('isValid')
    ->willReturn(true);

$this->mock(BarInterface::class)->serve()
    ->expects($this->at(1))
    ->method('isValid')
    ->willThrowException(new \Exception());

var_dump($this->mock(BarInterface::class)->serve()->isValid());
// bool(true)

var_dump($this->mock(BarInterface::class)->serve()->isValid());
// throws \Exception
```

## PHPUnit Integration

If you are extending PHPUnit `TestCase`, to simplify the cleaning phase we provide a `MokaCleanerTrait` which automatically runs `Moka::clean()` in `tearDown()`.
  
**Warning:** if you are defining your own `tearDown()`, you cannot use the trait! 
<!---
## Changelog

Please see [CHANGELOG](/CHANGELOG.md) for more information what has changed recently.
-->
## Testing

We highly suggest using [Paraunit](https://github.com/facile-it/paraunit) for a faster execution of tests:

```bash
$ composer install --dev
$ php vendor/bin/paraunit run
```
<!---
## Contributing

Please see [CONTRIBUTING](/CONTRIBUTING.md) for details.
-->
## Credits

- [Angelo Giuffredi](https://github.com/giuffre)
- [Alberto Villa](https://github.com/xzhavilla)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](/LICENSE) for more information.
