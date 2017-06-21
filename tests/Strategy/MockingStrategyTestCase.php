<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Factory\StubFactory;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Stub\StubSet;
use PHPUnit\Framework\TestCase;
use Tests\AbstractTestClass;
use Tests\FooTestClass;
use Tests\TestInterface;

abstract class MockingStrategyTestCase extends TestCase
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

    public function setUp()
    {
        $this->mock = $this->strategy->build(FooTestClass::class);

        // Mocking a StubSet is way too difficult.
        $this->stubs = StubFactory::fromArray([
            'isTrue' => false,
            'getInt' => 3,
            'withArgument' => 7,
            'throwException' => new \Exception()
        ]);

        $this->strategy->decorate($this->mock, $this->stubs);
    }

    public function testBuildClassSuccess()
    {
        $this->assertInstanceOf($this->strategy->getMockType(), $this->mock);
    }

    public function testBuildInterfaceSuccess()
    {
        $mock = $this->strategy->build(TestInterface::class);

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testBuildAbstractClassSuccess()
    {
        $mock = $this->strategy->build(AbstractTestClass::class);

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testBuildFakeFQCNSuccess()
    {
        $mock = $this->strategy->build($this->getRandomFQCN());

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testDecorateFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->strategy->decorate(new \stdClass(), $this->stubs);
    }

    public function testDecorateWrongTypeHintFailure()
    {
        $this->strategy->decorate($this->mock, StubFactory::fromArray([
            'getSelf' => 3
        ]));

        $this->expectException(\TypeError::class);
        $this->strategy->get($this->mock)->getSelf();
    }

    public function testDecorateOverrideFailure()
    {
        $this->strategy->decorate($this->mock, StubFactory::fromArray([
            'getInt' => 7,
            'throwException' => 1138
        ]));

        $this->assertSame(3, $this->strategy->get($this->mock)->getInt());
        $this->assertSame(3, $this->strategy->get($this->mock)->getInt());

        $this->expectException(\Exception::class);
        $this->strategy->get($this->mock)->throwException();
    }

    public function testSingleCallSuccess()
    {
        $this->assertSame(false, $this->strategy->get($this->mock)->isTrue());

        $this->expectException(\Exception::class);
        $this->strategy->get($this->mock)->throwException();
    }

    public function testMultipleCallsSuccess()
    {
        $this->assertSame(3, $this->strategy->get($this->mock)->getInt());
        $this->assertSame(3, $this->strategy->get($this->mock)->getInt());
    }

    public function testCallWithMissingArgumentFailure()
    {
        $this->expectException(\Error::class);

        $this->strategy->get($this->mock)->withArgument();
    }

    public function testCallWithWrongArgumentFailure()
    {
        $this->expectException(\TypeError::class);

        $this->strategy->get($this->mock)->withArgument('string');
    }

    public function testGetSuccess()
    {
        $this->assertInstanceOf(FooTestClass::class, $this->strategy->get($this->mock));
    }

    public function testGetFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->strategy->get(new \stdClass());
    }

    public function testGetMockTypeSuccess()
    {
        $this->assertInternalType('string', $this->strategy->getMockType());
    }

    protected function setStrategy(MockingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    protected function getRandomFQCN()
    {
        return 'foo_' . rand();
    }
}
