<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
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
     *
     * @throws MockNotCreatedException
     */
    public function build(string $fqcn)
    {
        try {
            return $this->generator->getMock(
                $fqcn,
                $methods = [],
                $arguments = [],
                $mockClassName = '',
                $callOriginalConstructor = false
            );
        } catch (\Exception $exception) {
            throw new MockNotCreatedException(
                sprintf(
                    'Unable to create mock object for FQCN %s',
                    $fqcn
                )
            );
        }
    }

    /**
     * @param MockObject $mock
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

            $partial = $mock->method($stub->getMethodName());
            $methodValue instanceof \Exception
                ? $partial->willThrowException($methodValue)
                : $partial->willReturn($methodValue);
        }
    }

    /**
     * @param MockObject $mock
     * @return MockObject
     *
     * @throws InvalidArgumentException
     */
    public function get($mock)
    {
        $this->checkMockType($mock);

        return $mock;
    }
}
