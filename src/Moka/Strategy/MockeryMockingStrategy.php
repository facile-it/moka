<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Mockery\MockInterface;
use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
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
     *
     * @throws MockNotCreatedException
     */
    public function build(string $fqcn)
    {
        try {
            return \Mockery::mock($fqcn)->shouldIgnoreMissing();
        } catch (\Throwable $exception) {
            throw new MockNotCreatedException(
                sprintf(
                    'Unable to create mock object for FQCN %s: %s',
                    $fqcn,
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * @param MockInterface $mock
     * @param StubSet $stubs
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function decorate($mock, StubSet $stubs)
    {
        $this->checkMockType($mock);

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
     *
     * @throws InvalidArgumentException
     */
    public function get($mock)
    {
        $this->checkMockType($mock);

        return $mock;
    }
}
