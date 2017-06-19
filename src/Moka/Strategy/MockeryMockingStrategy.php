<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Mockery\MockInterface;
use Moka\Stub\Stub;
use Moka\Stub\StubSet;

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
        return \Mockery::mock($fqcn);
    }

    /**
     * @param MockInterface $mock
     * @param StubSet $stubs
     * @return void
     */
    protected function doDecorate($mock, StubSet $stubs)
    {
        /** @var Stub $stub */
        foreach ($stubs as $stub) {
            $methodValue = $stub->getMethodValue();

            $partial = $mock->shouldReceive($stub->getMethodName())->zeroOrMoreTimes();
            $methodValue instanceof \Throwable
                ? $partial->andThrow($methodValue)
                : $partial->andReturn($methodValue);
        }
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
