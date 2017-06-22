<?php
declare(strict_types=1);

namespace Moka\Plugin\PHPUnit;

use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\Stub;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount as AnyInvokedCountMatcher;
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
     * @param Stub $stub
     * @return void
     */
    protected function doDecorate($mock, Stub $stub)
    {
        $methodName = $stub->getMethodName();
        $methodValue = $stub->getMethodValue();

        $partial = $mock->expects(new AnyInvokedCountMatcher())->method($methodName);
        $methodValue instanceof \Throwable
            ? $partial->willThrowException($methodValue)
            : $partial->willReturn($methodValue);
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
