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
class ProphecyMockingStrategy extends AbstractMockingStrategy
{
    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * PHPUnitMockingStrategy constructor.
     */
    public function __construct()
    {
        $this->prophet = new Prophet();
        $this->setMockType(ObjectProphecy::class);
    }

    /**
     * @param string $fqcn
     * @return ObjectProphecy
     *
     * @throws MockNotCreatedException
     */
    public function build(string $fqcn)
    {
        try {
            $mock = $this->prophet->prophesize($fqcn);

            $methods = array_filter(get_class_methods($fqcn), function ($method) {
                // Do not define stubs for magic methods.
                return !preg_match('/^__/', $method);
            });

            foreach ($methods as $methodName) {
                $mock->$methodName(Argument::cetera())->willReturn(null);
            }
        } catch (\Exception $exception) {
            throw new MockNotCreatedException(
                sprintf(
                    'Unable to create mock object for FQCN %s',
                    $fqcn
                )
            );
        }

        return $mock;
    }

    /**
     * @param ObjectProphecy $mock
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
            $methodName = $stub->getMethodName();
            $methodValue = $stub->getMethodValue();

            $methodValue instanceof \Exception
                ? $mock->$methodName(Argument::any())->willThrow($methodValue)
                : $mock->$methodName(Argument::any())->willReturn($methodValue);
        }
    }

    /**
     * @param ObjectProphecy $mock
     * @return object
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    public function get($mock)
    {
        $this->checkMockType($mock);

        try {
            return $mock->reveal();
        } catch (ObjectProphecyException $exception) {
            throw new MockNotCreatedException('Unable to create mock object');
        }
    }
}
