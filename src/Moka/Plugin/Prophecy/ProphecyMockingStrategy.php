<?php
declare(strict_types=1);

namespace Moka\Plugin\Prophecy;

use Moka\Exception\MissingDependencyException;
use Moka\Exception\MockNotCreatedException;
use Moka\Plugin\Prophecy\Token\MaxPriorityToken;
use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\Stub;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

/**
 * Class ProphecyMockingStrategy
 * @package Moka\Strategy
 */
class ProphecyMockingStrategy extends AbstractMockingStrategy
{
    const CLASS_NAME = Prophet::class;
    const PACKAGE_NAME = 'phpspec/prophecy';

    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * PHPUnitMockingStrategy constructor.
     *
     * @throws MissingDependencyException
     */
    public function __construct()
    {
        self::checkDependencies(self::CLASS_NAME, self::PACKAGE_NAME);

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
     * @param Stub $stub
     * @return void
     */
    protected function doDecorate($mock, Stub $stub)
    {
        $methodName = $stub->getMethodName();
        $methodValue = $stub->getMethodValue();

        /** @var MethodProphecy $partial */
        $partial = $mock->$methodName(new MaxPriorityToken());
        $methodValue instanceof \Throwable
            ? $partial->willThrow($methodValue)
            : $partial->willReturn($methodValue);
    }

    /**
     * @param ObjectProphecy $mock
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

    /**
     * @param ObjectProphecy $mock
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    protected function doCall($mock, string $name, array $arguments)
    {
        $this->checkMockType($mock);

        $methodProphecies = $mock->getMethodProphecies($name);
        if (count($methodProphecies) > 0) {
            return parent::doCall($mock->reveal(), $name, $arguments);
        }

        return parent::doCall($mock, $name, $arguments);
    }
}
