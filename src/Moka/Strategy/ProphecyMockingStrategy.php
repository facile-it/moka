<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Stub\Stub;
use Moka\Stub\StubSet;
use Prophecy\Argument;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

/**
 * Class ProphecyMockingStrategy
 * @package Moka\Strategy
 */
class ProphecyMockingStrategy implements MockingStrategyInterface
{
    /**
     * @var Prophet
     */
    private $generator;

    /**
     * PHPUnitMockingStrategy constructor.
     */
    public function __construct()
    {
        $this->generator = new Prophet();
    }

    /**
     * @param string $fqcn
     * @return object
     *
     * @throws MockNotCreatedException
     */
    public function build(string $fqcn)
    {
        $mock = $this->generator->prophesize($fqcn);

        $methods = array_filter(get_class_methods($fqcn), function ($method) {
            // Do not define stubs for magic methods.
            return !preg_match('/^__/', $method);
        });

        foreach ($methods as $method) {
            $mock->$method(Argument::cetera())->willReturn(null);
        }

        return $mock;
    }

    public function get($mock)
    {
        /** @var ObjectProphecy $mock */
        $this->checkMockType($mock);

        return $mock->reveal();
    }

    public function decorate($mock, StubSet $stubs)
    {
        /** @var ObjectProphecy $mock */
        $this->checkMockType($mock);

        /** @var Stub $stub */
        foreach ($stubs as $stub) {
            $methodValue = $stub->getMethodValue();
            $methodName = $stub->getMethodName();

            $methodValue instanceof \Exception
                ? $mock->$methodName(Argument::any())->willThrow($methodValue)
                : $mock->$methodName(Argument::any())->willReturn($methodValue);
        }

        try {
            return $mock;
        } catch (ObjectProphecyException $exception) {
            throw new MockNotCreatedException(sprintf('Unable to create a mock object for FQCN: %s', $mockingPromise->getFqcn()));
        }
    }

    private function checkMockType($mock)
    {
        if (!$mock instanceof ObjectProphecy) {
            throw new InvalidArgumentException();
        }
    }
}
