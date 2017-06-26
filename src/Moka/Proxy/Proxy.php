<?php
declare(strict_types=1);

namespace Moka\Proxy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;
use Moka\Exception\NotImplementedException;
use Moka\Factory\StubFactory;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Stub\StubSet;

/**
 * Class Proxy
 * @package Moka\Proxy
 */
class Proxy
{
    /**
     * @var string
     */
    private $fqcn;

    /**
     * @var StubSet
     */
    private $stubs;

    /**
     * @var MockingStrategyInterface
     */
    private $mockingStrategy;

    /**
     * @var object
     */
    private $mock;

    /**
     * Proxy constructor.
     * @param string $fqcn
     * @param MockingStrategyInterface $mockingStrategy
     */
    public function __construct(string $fqcn, MockingStrategyInterface $mockingStrategy)
    {
        $this->fqcn = $fqcn;
        $this->mockingStrategy = $mockingStrategy;
        $this->resetStubs();
    }

    /**
     * @return void
     */
    private function resetStubs()
    {
        $this->stubs = new StubSet();
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->getMock()->{$name}(...$arguments);
    }

    /**
     * @return object
     *
     * @throws MockNotCreatedException
     * @throws NotImplementedException
     * @throws InvalidArgumentException
     */
    private function getMock()
    {
        if (!$this->mock) {
            $this->mock = $this->mockingStrategy->build($this->fqcn);
            $this->decorateMock();
        }

        return $this->mock;
    }

    /**
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     * @throws NotImplementedException
     */
    private function decorateMock()
    {
        $this->mockingStrategy->decorate($this->getMock(), $this->stubs);
        $this->resetStubs();
    }

    /**
     * @param array $methodsWithValues
     * @return Proxy
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     * @throws NotImplementedException
     */
    public function stub(array $methodsWithValues): self
    {
        $this->stubs->addAll(
            StubFactory::fromArray($methodsWithValues)->all()
        );

        $this->decorateMock();

        return $this;
    }

    /**
     * @return object
     *
     * @throws MockNotServedException
     */
    public function serve()
    {
        try {
            return $this->mockingStrategy->get($this->getMock());
        } catch (\Exception $exception) {
            throw new MockNotServedException($exception->getMessage());
        }
    }
}
