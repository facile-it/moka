<?php

declare(strict_types=1);

namespace Moka\Plugin\PHPUnit;

use Moka\Exception\MissingDependencyException;
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
    const CLASS_NAME = MockGenerator::class;
    const PACKAGE_NAME = 'phpunit/phpunit-mock-object';

    /**
     * @var MockGenerator
     */
    private $generator;

    /**
     * PHPUnitMockingStrategy constructor.
     *
     * @throws MissingDependencyException
     */
    public function __construct()
    {
        self::checkDependencies(self::CLASS_NAME, self::PACKAGE_NAME);

        $this->generator = new MockGenerator();
        $this->setMockType(MockObject::class);
    }

    /**
     * @param string $fqcn
     * @return MockObject
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
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
