<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\PHPUnitMockingStrategy;
use Tests\TestClass;
use Tests\TestInterface;

class PHPUnitMockingStrategyTest extends MockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new PHPUnitMockingStrategy());
    }

    public function testBuildEmptyFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build('');
    }

    public function testBuildParseFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build('foo bar');
    }

    public function testBuildMultipleFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build('foo, bar');
    }

    public function testCallMissingMethodSuccess()
    {
        $mock = $this->strategy->build(TestClass::class);

        $this->assertInstanceOf(TestInterface::class, $this->strategy->get($mock)->getSelf());
    }

    public function testGetFakeFQCNSuccess()
    {
        $mock = $this->strategy->build('foo');

        $this->assertTrue(is_a($this->strategy->get($mock), 'foo'));
    }
}
