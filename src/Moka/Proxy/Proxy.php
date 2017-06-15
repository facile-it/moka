<?php
declare(strict_types=1);

namespace Moka\Proxy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;
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

    protected function resetStubs()
    {
        $this->stubs = new StubSet();
    }

    /**
     * @return object
     *
     * @throws MockNotServedException
     */
    public function serve()
    {
        if (!$this->mock) {
            $this->buildMock();
        }

        return $this->mockingStrategy->get($this->mock);
    }


    /**
     * @return object
     *
     * @throws MockNotServedException
     */
    private function buildMock()
    {
        try {
            $this->mock = $this->mockingStrategy->build($this->fqcn);
            $this->decorateMock();
        } catch (MockNotCreatedException $exception) {
            throw new MockNotServedException($exception->getMessage());
        }

        return $this->mock;
    }

    private function decorateMock()
    {
        $this->mockingStrategy->decorate($this->mock, $this->stubs);
        $this->resetStubs();
    }

    /**
     * @param array $methodsWithValues
     * @return Proxy
     *
     * @throws InvalidArgumentException
     */
    public function stub(array $methodsWithValues): self
    {
        $this->stubs->addAll(
            StubFactory::fromArray($methodsWithValues)->all()
        );
        if ($this->mock) {
            $this->decorateMock();
        }

        return $this;
    }
}
