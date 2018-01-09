<?php
declare(strict_types=1);

namespace Moka\Plugin\Mockery;

use Mockery;
use Mockery\MockInterface;
use Moka\Exception\MissingDependencyException;
use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\MethodStub;

/**
 * Class MockeryMockingStrategy
 * @package Moka\Strategy
 */
class MockeryMockingStrategy extends AbstractMockingStrategy
{
    const CLASS_NAME = Mockery::class;
    const PACKAGE_NAME = 'mockery/mockery';

    /**
     * MockeryMockingStrategy constructor.
     *
     * @throws MissingDependencyException
     */
    public function __construct()
    {
        self::checkDependencies(self::CLASS_NAME, self::PACKAGE_NAME);

        $this->setMockType(MockInterface::class);
    }

    /**
     * @param string $fqcn
     * @return MockInterface
     */
    protected function doBuild(string $fqcn)
    {
        return Mockery::mock($fqcn);
    }

    /**
     * @param MockInterface $mock
     * @param MethodStub $stub
     * @return void
     */
    protected function doDecorateWithMethod($mock, MethodStub $stub)
    {
        $methodName = $stub->getName();
        $methodValue = $stub->getValue();

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $partial = $mock->shouldReceive($methodName)->zeroOrMoreTimes();
        $methodValue instanceof \Throwable
            ? $partial->andThrow($methodValue)
            : $partial->andReturn($methodValue);
    }

    /**
     * @param MockInterface $mock
     * @return MockInterface
     */
    protected function doGet($mock)
    {
        return $mock;
    }
}
