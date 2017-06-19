<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Factory\StubFactory;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Stub\StubSet;
use PHPUnit\Framework\TestCase;
use Tests\TestClass;

abstract class MockingStrategyTestCase extends TestCase
{
    /**
     * @var MockingStrategyInterface
     */
    protected $strategy;

    /**
     * @var StubSet
     */
    protected $stubs;

    public function setUp()
    {
        // Mocking a StubSet is way too difficult.
        $this->stubs = StubFactory::fromArray([
            'isValid' => false,
            'getInt' => 3,
            'throwException' => new \Exception()
        ]);
    }

    public function testBuildSuccess()
    {
        $mock = $this->strategy->build(TestClass::class);

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testDecorateSingleCallSuccess()
    {
        $mock = $this->strategy->build(TestClass::class);
        $this->strategy->decorate($mock, $this->stubs);
        $this->assertSame(false, $this->strategy->get($mock)->isValid());

        $this->expectException(\Exception::class);
        $this->strategy->get($mock)->throwException();
    }

    public function testDecorateMultipleCallsSuccess()
    {
        $mock = $this->strategy->build(TestClass::class);
        $this->strategy->decorate($mock, $this->stubs);
        $this->assertSame(3, $this->strategy->get($mock)->getInt());
        $this->assertSame(3, $this->strategy->get($mock)->getInt());
    }

    public function testDecorateFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->strategy->decorate(new \stdClass(), $this->stubs);
    }

    public function testGetSuccess()
    {
        $mock = $this->strategy->build(TestClass::class);

        $this->assertInstanceOf(TestClass::class, $this->strategy->get($mock));
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
}
