# Moka - Shorthand for Creating Mock Objects

[![Packagist](https://img.shields.io/packagist/l/facile-it/moka.svg)](/LICENSE)
[![GitHub release](https://img.shields.io/github/release/facile-it/moka.svg)](https://packagist.org/packages/facile-it/moka)
[![Travis](https://img.shields.io/travis/facile-it/moka/master.svg)](https://travis-ci.org/facile-it/moka/branches)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/facile-it/moka.svg)](https://scrutinizer-ci.com/g/facile-it/moka/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/facile-it/moka.svg)](https://scrutinizer-ci.com/g/facile-it/moka/?branch=master)
[![Packagist](https://img.shields.io/packagist/dt/facile-it/moka.svg)](https://packagist.org/packages/facile-it/moka)

Tired of spending most of your testing time mocking objects like there's no tomorrow? **Yes.**  
**Moka** provides you with three simple methods to reduce your effort on such a tedious task, and with an incredible abstraction layer between the most popular mock engines and **you**.

## Installation

You can simply install the package via composer:

```bash
composer require --dev facile-it/moka
```

## Usage

To use **Moka** in your tests simply `use` the `MokaPHPUnitTrait` (see generators section [below](#strategies)) and run `Moka::clean()` before every test. A simple interface will let you create *mock* objects, *serve* them easily, and decorate them with *stub* methods with a fluent interface.

A complete example follows:

```php
<?php

namespace Foo\Tests;

use Moka\Moka;
use Moka\Traits\MokaPHPUnitTrait;

class FooTest extends \AnyTestCase
{
    use MokaPHPUnitTrait;
    
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
    
    //...
}
```

Alternatively, instead of using the trait and `$this->mock(/* ... */)`, you can call `Moka::phpunit(string $fqcn, string $alias = null): Proxy`.

Being such a simple project, **Moka** can be integrated in an already existing test suite with no effort.

**Notice:** If you are extending PHPUnit `TestCase`, to simplify the cleaning phase we provide a `MokaCleanerTrait` which automatically runs `Moka::clean()` in `tearDown()`.
**Warning:** if you are defining your own `tearDown()`, you cannot use the trait!

```php
<?php

namespace Foo\Tests;

use Moka\Traits\MokaCleanerTrait;
use Moka\Traits\MokaPHPUnitTrait;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{
    use MokaPHPUnitTrait, MokaCleanerTrait;
    
    public function setUp()
    {
        // No call to Moka::clean() needed.
        
        // ...
    }
    
    // ...
}
```

## <a name="reference"></a>Reference

### `mock(string $fqcn, string $alias = null): Proxy`

Creates (if not existing already) a proxy containing a mock object according to selected strategy for the class identified by `$fqcn` and optionally assigns an `$alias` to it.

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

### `serve() // Actual mock object instance`

Regain the control returning the actual mock object (in the example below, PHPUnit's) unwrapped from the proxy.

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

## <a name="strategies"></a>Supported mock object generators

Currently we ship **Moka** with built-in support for [PHPUnit](https://phpunit.de/manual/current/en/test-doubles.html) mock objects.  
We support other generators as well, but you need to install the relevant package:
- [Prophecy](https://github.com/phpspec/prophecy) -> [phpspec/prophecy](http://packagist.io/phpspec/prophecy) 
- [Mockery](http://docs.mockery.io/en/latest/) -> [mockery/mockery](http://packagist.io/mockery/mockery)
- [Phake](http://phake.readthedocs.io/) -> [phake/phake](http://packagist.io/phake/phake)

We provide a specific trait for each supported strategy, as well as a static method:

- `MokaPHPUnitTrait`
- `MokaProphecyTrait`
- `MokaMockeryTrait`
- `MokaPhakeTrait`

Every trait defines its own `mock(string $fqcn, string $alias = null): Proxy`, as described in the **[Reference](#reference)**.
<!---
## Changelog

Please see [CHANGELOG](/CHANGELOG.md) for more information what has changed recently.
-->
## Testing

We highly suggest using [Paraunit](https://github.com/facile-it/paraunit) for a faster execution of tests:

```bash
composer global require facile-it/paraunit

paraunit run
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
