<?php
declare(strict_types=1);

namespace Moka\Plugin\PHPUnit;

use Moka\Exception\MissingDependencyException;
use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\MethodStub;
use PHPUnit\Framework\MockObject\Generator;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class PHPUnitMockingStrategy
 * @package Moka\Strategy
 */
class PHPUnitMockingStrategy extends AbstractMockingStrategy
{
    private const CLASS_NAME = Generator::class;
    private const PACKAGE_NAME = 'phpunit/phpunit-mock-objects';

    /**
     * @var Generator
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

        $this->generator = new Generator();
        $this->setMockType(MockObject::class);
    }

    /**
     * @param string $fqcn
     * @return MockObject
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     */
    protected function doBuild(string $fqcn): MockObject
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
     * @param MethodStub $stub
     *
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    protected function doDecorateWithMethod($mock, MethodStub $stub): void
    {
        $methodName = $stub->getName();
        $methodValue = $stub->getValue();

        $partial = $mock->expects(new AnyInvokedCount())->method($methodName);
        $methodValue instanceof \Throwable
            ? $partial->willThrowException($methodValue)
            : $partial->willReturn($methodValue);
    }

    /**
     * @param MockObject $mock
     * @return MockObject
     */
    protected function doGet($mock): MockObject
    {
        return $mock;
    }
}
