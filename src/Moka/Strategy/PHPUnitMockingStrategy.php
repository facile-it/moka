<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Stub\Stub;
use Moka\Stub\StubSet;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class PHPUnitMockingStrategy
 * @package Moka\Strategy
 */
class PHPUnitMockingStrategy extends AbstractMockingStrategy
{
    /**
     * @var MockGenerator
     */
    private $generator;

    /**
     * PHPUnitMockingStrategy constructor.
     */
    public function __construct()
    {
        $this->generator = new MockGenerator();
        $this->setMockType(MockObject::class);
    }

    /**
     * @param string $fqcn
     * @return MockObject
     */
    protected function doBuild(string $fqcn)
    {
        return $this->generator->getMock(
            $fqcn,
            $methods = [],
            $arguments = [],
            $mockClassName = '',
            $callOriginalConstructor = false
        );
    }

    /**
     * @param MockObject $mock
     * @param StubSet $stubs
     * @return void
     */
    protected function doDecorate($mock, StubSet $stubs)
    {
        /** @var Stub $stub */
        foreach ($stubs as $stub) {
            $methodValue = $stub->getMethodValue();

            $partial = $mock->method($stub->getMethodName());
            $methodValue instanceof \Throwable
                ? $partial->willThrowException($methodValue)
                : $partial->willReturn($methodValue);
        }
    }

    /**
     * @param MockObject $mock
     * @return MockObject
     */
    protected function doGet($mock)
    {
        return $mock;
    }
}
