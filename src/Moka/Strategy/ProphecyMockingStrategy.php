<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\Prophecy\NoPriorityToken;
use Moka\Stub\Stub;
use Moka\Stub\StubSet;
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
     */
    protected function doBuild(string $fqcn)
    {
        return $this->prophet->prophesize($fqcn);
    }

    /**
     * @param ObjectProphecy $mock
     * @param StubSet $stubs
     * @return void
     */
    protected function doDecorate($mock, StubSet $stubs)
    {
        /** @var Stub $stub */
        foreach ($stubs as $stub) {
            $methodValue = $stub->getMethodValue();

            $partial = $mock->{$stub->getMethodName()}(new NoPriorityToken());
            $methodValue instanceof \Throwable
                ? $partial->willThrow($methodValue)
                : $partial->willReturn($methodValue);
        }
    }

    /**
     * @param object $mock
     * @return mixed
     *
     * @throws MockNotCreatedException
     */
    protected function doGet($mock)
    {
        try {
            return $mock->reveal();
        } catch (ObjectProphecyException $exception) {
            throw new MockNotCreatedException(
                sprintf(
                    'Cannot create mock object: %s',
                    $exception->getMessage()
                )
            );
        }
    }
}
