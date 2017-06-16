<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\Prophecy\LowPriorityToken;
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
     *
     * @throws MockNotCreatedException
     */
    public function build(string $fqcn)
    {
        try {
            $mock = $this->prophet->prophesize($fqcn);

            $methodNames = $this->filterMethods($fqcn);
            foreach ($methodNames as $methodName) {
                $mock->$methodName(new NoPriorityToken())->willReturn(null);
            }
        } catch (\Exception $exception) {
            // This should never be reached.
            throw new MockNotCreatedException(
                sprintf(
                    'Unable to create mock object for FQCN %s: %s',
                    $fqcn,
                    $exception->getMessage()
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

            $methodValue instanceof \Throwable
                ? $mock->$methodName(new LowPriorityToken())->willThrow($methodValue)
                : $mock->$methodName(new LowPriorityToken())->willReturn($methodValue);
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
            throw new MockNotCreatedException(
                sprintf(
                    'Unable to create mock object: %s',
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * @param string $fqcn
     * @return string[]
     */
    protected function filterMethods(string $fqcn): array
    {
        // The result is empty for nonexistent FQCNs (or empty ones).
        $methodNames = get_class_methods($fqcn) ?: [];

        // Filter magic and final methods.
        return array_filter($methodNames, function ($methodName) use ($fqcn) {
            if (preg_match('/^__/', $methodName)) {
                return false;
            }

            $reflectionMethod = new \ReflectionMethod($fqcn, $methodName);
            if ($reflectionMethod->isFinal()) {
                return false;
            }

            return true;
        });
    }
}
