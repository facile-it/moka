<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MissingDependencyException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Factory\StubFactory;
use Moka\Stub\MethodStub;
use Moka\Stub\PropertyStub;
use Moka\Stub\StubInterface;
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
     * @param StubInterface[] $stubs
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws NotImplementedException
     * @throws \LogicException
     */
    public function decorate($mock, array $stubs): void
    {
        $this->checkMockType($mock);

        $stubs = StubFactory::fromArray($stubs);

        foreach ($stubs as $stub) {
            if ($stub instanceof PropertyStub) {
                $this->doDecorateWithProperty($mock, $stub);
            }

            if ($stub instanceof MethodStub) {
                $this->doDecorateWithMethod($mock, $stub);
            }
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
     * @param object $mock
     * @param string $methodName
     * @return mixed
     *
     * @throws NotImplementedException
     * @throws InvalidArgumentException
     * @throws \Error
     */
    public function call($mock, string $methodName)
    {
        $this->checkMockType($mock);

        return $this->doCall($mock, $methodName);
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
                    'Mock object must be an instance of "%s", "%s" given',
                    $this->mockType,
                    gettype($mock)
                )
            );
        }
    }

    /**
     * @return void
     *
     * @throws NotImplementedException
     */
    private function verifyMockType(): void
    {
        if (!$this->mockType) {
            throw new NotImplementedException('Mock type was not defined');
        }
    }

    /**
     * @param string $fqcn
     * @return object
     */
    abstract protected function doBuild(string $fqcn);

    /**
     * @param object $mock
     * @param PropertyStub $stub
     * @return void
     */
    protected function doDecorateWithProperty($mock, PropertyStub $stub): void
    {
        $mock->{$stub->getName()} = $stub->getValue();
    }

    /**
     * @param object $mock
     * @param MethodStub $stub
     * @return void
     */
    abstract protected function doDecorateWithMethod($mock, MethodStub $stub): void;

    /**
     * @param object $mock
     * @return object
     */
    abstract protected function doGet($mock);

    /**
     * @param object $mock
     * @param string $methodName
     * @return mixed
     * @throws \Error
     */
    protected function doCall($mock, string $methodName)
    {
        throw new \Error(
            sprintf(
                'Undefined property: %s::$%s',
                get_class($this),
                $methodName
            )
        );
    }
}
