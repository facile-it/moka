# Moka - Shorthand for Creating Mock Objects

[![Packagist](https://img.shields.io/packagist/l/facile-it/moka.svg)](/LICENSE)
[![GitHub release](https://img.shields.io/github/release/facile-it/moka.svg)](https://packagist.org/packages/facile-it/moka)
[![Travis](https://img.shields.io/travis/facile-it/moka/master.svg)](https://travis-ci.org/facile-it/moka/branches)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/facile-it/moka.svg)](https://scrutinizer-ci.com/g/facile-it/moka/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/facile-it/moka.svg)](https://scrutinizer-ci.com/g/facile-it/moka/?branch=master)
[![Packagist](https://img.shields.io/packagist/dt/facile-it/moka.svg)](https://packagist.org/packages/facile-it/moka)

Tired of spending most of your testing time mocking objects like there's no tomorrow? **Yes.**  
**Moka** provides you with two simple methods to reduce your effort on such a tedious task, and with an incredible abstraction layer between the most popular mock engines and **you**.

## Installation

You can simply install the package via composer:

```bash
composer require --dev facile-it/moka
```

## Usage

To use **Moka** in your tests simply `use` function `Moka\Plugin\PHPUnit\moka()` (see generators section [below](#strategies)) and run `Moka::clean()` before every test. A simple interface will let you create *moka* (mock) objects and decorate them with *stub* methods with a fluent interface.

A complete example follows:

```php
<?php

namespace Foo\Tests;

use Moka\Moka;
use function Moka\Plugin\PHPUnit\moka;

class FooTest extends \AnyTestCase
{
    private $foo;
    
    protected function setUp()
    {
        Moka::clean();
        
        // The subject of the test.
        $this->foo = new Foo(
            moka(BarInterface::class)->stub([
                // Method name => return value.
                'method1' => moka(AcmeInterface::class),
                'method2' => true // Any return value.
            ])
        );
    }
    
    //...
}
```

Alternatively, instead of using `moka()`, you can call `Moka::phpunit(string $fqcnOrAlias, string $alias = null): ProxyInterface`.

Being such a simple project, **Moka** can be integrated in an already existing test suite with no effort.

**Notice:** if you are extending PHPUnit `TestCase`, to simplify the cleaning phase we provide a `MokaCleanerTrait` which automatically runs `Moka::clean()` in `tearDown()`.
**Warning:** if you are defining your own `tearDown()`, you cannot use the trait!

```php
<?php

namespace Foo\Tests;

use function Moka\Plugin\PHPUnit\moka;
use Moka\Traits\MokaCleanerTrait;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{
    use MokaCleanerTrait;
    
    protected function setUp()
    {
        // No call to Moka::clean() needed.
        
        // ...
    }
    
    // ...
}
```

<a name='original-mock'></a>You can rely on the original mock object implementation to be accessible (in the example below, PHPUnit's):

```php
moka(BarInterface::class, 'bar')
    ->expects($this->at(0))
    ->method('isValid')
    ->willReturn(true);

moka('bar')
    ->expects($this->at(1))
    ->method('isValid')
    ->willThrowException(new \Exception());

var_dump(moka('bar')->isValid());
// bool(true)

var_dump(moka('bar')->isValid());
// throws \Exception
```

## <a name='reference'></a>Reference

### `moka(string $fqcnOrAlias, string $alias = null): ProxyInterface`

Creates a proxy containing a mock object (according to the selected strategy) for the provided *FQCN* and optionally assigns an `$alias` to it to be able to get it later:

```php
$mock1 = moka(FooInterface::class); // Creates the mock for FooInterface.
$mock2 = moka(FooInterface::class); // Gets a different mock.

var_dump($mock1 === $mock2);
// bool(false)
```

The `$alias` allows you to store mock instances:

```php
$mock1 = moka(FooInterface::class, 'foo'); // Creates a mock for FooInterface.
$mock2 = moka('foo'); // Get the mock previously created.

var_dump($mock1 === $mock2);
// bool(true)
```

### `ProxyInterface::stub(array $methodsWithValues): ProxyInterface`

Accepts an array of method stubs with format `[$methodName => $methodValue]`, where `$methodName` **must** be a string and `$methodValue` can be of any type, including another mock object or an exception instance:

```php
$mock = moka(BarInterface::class)->stub([
    'isValid' => true,
    'getMock' => moka(AcmeInterface::class),
    'throwException' => new \Exception()
]);

var_dump($mock->isValid());
// bool(true)
```

**Notice:** the stub is valid for **any** invocation of the method and it cannot be overridden.  
If you need more granular control over invocation strategies, you can get [access to the original mock object implementation](#original-mock).

## <a name='strategies'></a>Supported mock object generators

Currently we ship **Moka** with built-in support for [PHPUnit](https://phpunit.de/manual/current/en/test-doubles.html) mock objects.  
We support other generators as well, but you need to install the relevant packages to make them work:

- [Prophecy](https://github.com/phpspec/prophecy) -> [phpspec/prophecy](https://packagist.org/packages/phpspec/prophecy)
- [Mockery](http://docs.mockery.io/en/latest/) -> [mockery/mockery](https://packagist.org/packages/mockery/mockery)
- [Phake](http://phake.readthedocs.io/) -> [phake/phake](https://packagist.org/packages/phake/phake)

We provide a specific `moka()` function for each supported strategy, as well as a static method (self documented in the function itself):

- `Moka\Plugin\PHPUnit\moka`
- `Moka\Plugin\Prophecy\moka`
- `Moka\Plugin\Mockery\moka`
- `Moka\Plugin\Phake\moka`

## Plugin development

If you feel a genius and want to create your own mock generator (or add support for an existing one), just implement `Moka\Plugin\PluginInterface` and the relative `Moka\Strategy\MockingStrategyInterface`:

```php
<?php

namespace Moka\Plugin\YourOwn;

use Moka\Plugin\PluginInterface;
use Moka\Strategy\MockingStrategyInterface;

class YourOwnPlugin implements PluginInterface
{
    public static function getStrategy(): MockingStrategyInterface 
    {
       return new YourOwnMockingStrategy();
    }
}
```

Extend `AbstractMockingStrategy` for an easier (and stricter) implementation of your strategy:

```php
<?php

namespace Moka\Plugin\YourOwn;

use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\Stub;

class YourOwnMockingStrategy extends AbstractMockingStrategy
{
    public function __construct()
    {
        // TODO: Implement __construct() method.
    }
    
    protected function doBuild(string $fqcn)
    {
        // TODO: Implement doBuild() method.
    }
    
    protected function doDecorate($mock, Stub $stub)
    {
        // TODO: Implement doDecorate() method.
    }
    
    protected function doGet($mock)
    {
        // TODO: Implement doGet() method.
    }

    protected function doCall($target, string $name, array $arguments)
    {
        // Override doCall() if you need special behavior.
        // See ProphecyMockingStrategy::doCall().
    }
}
```

**Warning:** your plugin *FQCN* must match the template `Moka\Plugin\YourOwn\YourOwnPlugin`, where `YourOwn` is the name of the plugin.  
Both your plugin and your strategy must pass our test cases (please install [phpunit/phpunit](https://packagist.org/packages/phpunit/phpunit) to run them):

- `MokaPluginTestCase`
- `MokaMockingStrategyTestCase`

Let [us](#credits) know of any **Moka**-related development!
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
## <a name='credits'></a>Credits

- [Angelo Giuffredi](https://github.com/giuffre)
- [Alberto Villa](https://github.com/xzhavilla)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](/LICENSE) for more information.
