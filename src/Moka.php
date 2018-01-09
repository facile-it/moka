<?php
declare(strict_types=1);

namespace Moka;

use Mockery\MockInterface;
use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MissingDependencyException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Plugin\PluginHelper;
use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;
use Moka\Strategy\MockingStrategyInterface;
use Phake_IMock as PhakeMock;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

/**
 * Class Moka
 * @package Moka
 *
 * @method static MockInterface|ProxyInterface mockery(string $fqcnOrAlias, string $alias = null)
 * @method static PhakeMock|ProxyInterface phake(string $fqcnOrAlias, string $alias = null)
 */
class Moka
{
    /**
     * @var array|MockingStrategyInterface[]
     */
    private static $mockingStrategies = [];

    /**
     * @param string $name
     * @param array $arguments
     * @return ProxyInterface
     *
     * @throws NotImplementedException
     * @throws InvalidIdentifierException
     * @throws MockNotCreatedException
     * @throws MissingDependencyException
     */
    public static function __callStatic(string $name, array $arguments): ProxyInterface
    {
        if (!isset(self::$mockingStrategies[$name])) {
            self::$mockingStrategies[$name] = PluginHelper::load($name);
        }

        $fqcnOrAlias = $arguments[0];
        $alias = $arguments[1] ?? null;
        $mockingStrategy = self::$mockingStrategies[$name];

        return ProxyBuilderFactory::get($mockingStrategy)->getProxy($fqcnOrAlias, $alias);
    }

    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return MockObject|ProxyInterface
     */
    public static function phpunit(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        /** @var ProxyInterface|ProxyTrait $proxy */
        $proxy = self::__callStatic('phpunit', [$fqcnOrAlias, $alias]);

        if (null !== $testCase = self::getCurrentTestCase()) {
            $testCase->registerMockObject($proxy->__moka_getMock());
        }

        return $proxy;
    }

    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ObjectProphecy|ProxyInterface
     */
    public static function prophecy(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        /** @var ProxyInterface|ProxyTrait $proxy */
        $proxy = self::__callStatic('prophecy', [$fqcnOrAlias, $alias]);

        if (null !== $testCase = self::getCurrentTestCase()) {
            $prophetProperty = new \ReflectionProperty(
                TestCase::class,
                'prophet'
            );

            $prophetProperty->setAccessible(true);
            if (null === $prophet = $prophetProperty->getValue($testCase)) {
                $prophet = new Prophet();
                $prophetProperty->setValue($testCase, $prophet);
            }

            $propheciesProperty = new \ReflectionProperty(
                Prophet::class,
                'prophecies'
            );
            $propheciesProperty->setAccessible(true);

            /** @var ObjectProphecy[] $prophecies */
            $prophecies = $propheciesProperty->getValue($prophet) ?: [];
            $prophecies[] = $proxy->__moka_getMock();

            $propheciesProperty->setValue($prophet, $prophecies);
        }

        return $proxy;
    }

    /**
     * @return void
     */
    public static function clean()
    {
        ProxyBuilderFactory::reset();
    }

    /**
     * @return TestCase|null
     */
    private static function getCurrentTestCase()
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
        foreach ($backtrace as $frame) {
            if (!isset($frame['object'])) {
                continue;
            }

            $object = $frame['object'];
            if ($object instanceof TestCase) {
                return $object;
            }

            // @codeCoverageIgnoreStart
            return null;
            // @codeCoverageIgnoreEnd
        }

        // @codeCoverageIgnoreStart
        return null;
        // @codeCoverageIgnoreEnd
    }
}
