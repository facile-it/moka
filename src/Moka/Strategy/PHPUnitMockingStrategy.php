<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 15/06/2017
 * Time: 15:48
 */

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Stub\StubSet;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class PHPUnitMockingStrategy
 * @package Moka\Strategy
 */
class PHPUnitMockingStrategy implements MockingStrategyInterface
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
            $mock = $this->generator->getMock(
                $fqcn,
                $methods = [],
                $arguments = [],
                $mockClassName = '',
                $callOriginalConstructor = false
            );
        } catch (\Exception $exception) {
            throw new MockNotCreatedException(sprintf('Unable to create a mock object for FQCN: %s', $fqcn));
        }

        return $mock;
    }

    public function get($mock)
    {
        /** @var MockObject $mock */
        $this->checkMockType($mock);

        return $mock;
    }

    private function checkMockType($mock)
    {
        if (!$mock instanceof MockObject) {
            throw new InvalidArgumentException(
                sprintf(
                    'The first argument must be of type %s, %s given',
                    MockObject::class,
                    gettype($mock)
                )
            );
        }
    }

    public function decorate($mock, StubSet $stubs)
    {
        /** @var MockObject $mock */
        $this->checkMockType($mock);

        foreach ($stubs as $stub) {
            $methodValue = $stub->getMethodValue();

            $partial = $mock->method($stub->getMethodName());

            if ($methodValue instanceof \Exception) {
                $partial
                    ->willThrowException($methodValue);
            } else {
                $partial
                    ->willReturn($methodValue);
            }
        }

        return $mock;
    }
}
