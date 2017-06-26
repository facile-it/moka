<?php

declare(strict_types=1);

namespace Moka\Tests;

use Moka\Exception\InvalidArgumentException;
use Moka\Factory\StubFactory;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Stub\StubSet;
use PHPUnit\Framework\TestCase;
use Tests\AbstractTestClass;
use Tests\BarTestClass;
use Tests\FooTestClass;
use Tests\TestInterface;

abstract class MokaMockingStrategyTestCase extends TestCase
{
    /**
     * @var MockingStrategyInterface
     */
    protected $strategy;

    /**
     * @var object
     */
    protected $mock;

    /**
     * @var StubSet
     */
    protected $stubs;

    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $methodsWithValues = [];

    final public function testGetMockTypeSuccess()
    {
        $this->assertInternalType('string', $this->strategy->getMockType());
    }

    final public function testBuildClassSuccess()
    {
        $this->assertInstanceOf($this->strategy->getMockType(), $this->mock);
    }

    final public function testBuildInterfaceSuccess()
    {
        $mock = $this->strategy->build(TestInterface::class);

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    final public function testBuildAbstractClassSuccess()
    {
        $mock = $this->strategy->build(AbstractTestClass::class);

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    final public function testDecorateFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->strategy->decorate(new \stdClass(), $this->stubs);
    }

    final public function testDecorateWrongTypeHintFailure()
    {
        $this->strategy->decorate($this->mock, StubFactory::fromArray([
            'getSelf' => mt_rand(),
        ]));

        $this->expectException(\TypeError::class);
        $this->strategy->get($this->mock)->getSelf();
    }

    final public function testDecorateSingleCallSuccess()
    {
        $this->assertSame($this->methodsWithValues['isTrue'], $this->strategy->get($this->mock)->isTrue());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($this->methodsWithValues['throwException']->getMessage());
        $this->strategy->get($this->mock)->throwException();
    }

    final public function testDecorateMultipleCallsSuccess()
    {
        $this->assertSame($this->methodsWithValues['getInt'], $this->strategy->get($this->mock)->getInt());
        $this->assertSame($this->methodsWithValues['getInt'], $this->strategy->get($this->mock)->getInt());
    }

    final public function testDecorateOverriddenCallsFailure()
    {
        $this->strategy->decorate($this->mock, StubFactory::fromArray([
            'getInt' => mt_rand(),
            'throwException' => mt_rand(),
        ]));

        $this->assertSame($this->methodsWithValues['getInt'], $this->strategy->get($this->mock)->getInt());
        $this->assertSame($this->methodsWithValues['getInt'], $this->strategy->get($this->mock)->getInt());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($this->methodsWithValues['throwException']->getMessage());
        $this->strategy->get($this->mock)->throwException();
    }

    final public function testDecorateCallWithArgumentSuccess()
    {
        $this->assertSame($this->methodsWithValues['withArgument'], $this->strategy->get($this->mock)->withArgument(mt_rand()));
    }

    final public function testDecorateCallWithMissingArgumentFailure()
    {
        $this->expectException(\Error::class);

        $this->strategy->get($this->mock)->withArgument();
    }

    final public function testDecorateCallWithWrongArgumentFailure()
    {
        $this->expectException(\TypeError::class);

        $this->strategy->get($this->mock)->withArgument('string');
    }

    final public function testGetSuccess()
    {
        $this->assertInstanceOf($this->className, $this->strategy->get($this->mock));
    }

    final public function testGetFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->strategy->get(new \stdClass());
    }

    protected function setUp()
    {
        $this->className = [
            FooTestClass::class,
            BarTestClass::class,
        ][random_int(0, 1)];

        $this->methodsWithValues = [
            'isTrue' => (bool) random_int(0, 1),
            'getInt' => mt_rand(),
            'withArgument' => mt_rand(),
            'throwException' => new \Exception(''.mt_rand()),
        ];

        $this->mock = $this->strategy->build($this->className);

        // Mocking a StubSet is way too difficult.
        $this->stubs = StubFactory::fromArray($this->methodsWithValues);

        $this->strategy->decorate($this->mock, $this->stubs);
    }

    final protected function setStrategy(MockingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    final protected function getRandomFQCN()
    {
        return 'foo_'.mt_rand();
    }
}
