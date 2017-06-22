<?php
declare(strict_types=1);

namespace Moka\Plugin\Mockery;

use Mockery;
use Mockery\MockInterface;
use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\Stub;

/**
 * Class MockeryMockingStrategy
 * @package Moka\Strategy
 */
class MockeryMockingStrategy extends AbstractMockingStrategy
{
    /**
     * MockeryMockingStrategy constructor.
     */
    public function __construct()
    {
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
     * @param Stub $stub
     * @return void
     */
    protected function doDecorate($mock, Stub $stub)
    {
        $methodName = $stub->getMethodName();
        $methodValue = $stub->getMethodValue();

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
