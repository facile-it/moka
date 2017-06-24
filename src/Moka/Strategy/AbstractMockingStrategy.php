<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MissingDependencyException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Stub\Stub;
use Moka\Stub\StubSet;

/**
 * Class AbstractMockingStrategy
 * @package Moka\Strategy
 */
abstract class AbstractMockingStrategy implements MockingStrategyInterface
{
    /**
     * @var string
     */
    private $mockType;

    /**
     * @param string $fqcn
     * @return object
     *
     * @throws MockNotCreatedException
     */
    public function build(string $fqcn)
    {
        try {
            return $this->doBuild($fqcn);
        } catch (\Throwable $exception) {
            throw new MockNotCreatedException(
                sprintf(
                    'Cannot create mock object for FQCN "%s": %s',
                    $fqcn,
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * @param object $mock
     * @param StubSet $stubs
     * @return void
     *
     * @throws NotImplementedException
     * @throws InvalidArgumentException
     */
    public function decorate($mock, StubSet $stubs)
    {
        $this->checkMockType($mock);

        /** @var Stub $stub */
        foreach ($stubs as $stub) {
            $this->doDecorate($mock, $stub);
        }
    }

    /**
     * @param object $mock
     * @return object
     *
     * @throws NotImplementedException
     * @throws InvalidArgumentException
     */
    public function get($mock)
    {
        $this->checkMockType($mock);

        return $this->doGet($mock);
    }

    /**
     * @return string
     *
     * @throws NotImplementedException
     */
    public function getMockType(): string
    {
        $this->verifyMockType();

        return $this->mockType;
    }

    /**
     * @param string $dependencyClassName
     * @param string $dependencyPackageName
     *
     * @throws MissingDependencyException
     */
    final protected static function checkDependencies(string $dependencyClassName, string $dependencyPackageName)
    {
        if (!class_exists($dependencyClassName)) {
            throw new MissingDependencyException(
                sprintf(
                    'Class "%s" does not exist, please install package "%s"',
                    $dependencyClassName,
                    $dependencyPackageName
                )
            );
        }
    }

    /**
     * @param string $fqcn
     */
    final protected function setMockType(string $fqcn)
    {
        $this->mockType = $fqcn;
    }

    /**
     * @param object $mock
     *
     * @throws NotImplementedException
     * @throws InvalidArgumentException
     */
    final protected function checkMockType($mock)
    {
        $this->verifyMockType();

        if (!is_a($mock, $this->mockType)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Mock must be of type "%s", "%s" given',
                    $this->mockType,
                    gettype($mock)
                )
            );
        }
    }

    /**
     * @param string $fqcn
     * @return object
     */
    abstract protected function doBuild(string $fqcn);

    /**
     * @param object $mock
     * @param Stub $stub
     * @return void
     */
    abstract protected function doDecorate($mock, Stub $stub);

    /**
     * @param object $mock
     * @return object
     */
    abstract protected function doGet($mock);

    /**
     * @return void
     *
     * @throws NotImplementedException
     */
    private function verifyMockType()
    {
        if (!$this->mockType) {
            throw new NotImplementedException('Mock type was not defined');
        }
    }
}
