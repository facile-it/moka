<?php
declare(strict_types=1);

namespace Moka\Generator;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Trait ProxyTrait
 * @package Moka\Generator
 */
trait ProxyTrait
{
    /**
     * @var object
     */
    private $mock;

    /**
     * @var MockingStrategyInterface
     */
    private $mockingStrategy;

    /**
     * ProxyTrait constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return object
     */
    public function __moka_getMock()
    {
        return $this->mock;
    }

    /**
     * @param object $mock
     * @return ProxyInterface|ProxyTrait
     */
    public function __moka_setMock($mock): self
    {
        $this->mock = $mock;

        return $this;
    }

    /**
     * @param MockingStrategyInterface $mockingStrategy
     * @return ProxyInterface|ProxyTrait
     */
    public function __moka_setMockingStrategy(MockingStrategyInterface $mockingStrategy): self
    {
        $this->mockingStrategy = $mockingStrategy;

        return $this;
    }

    /**
     * @param array $methodsWithValues
     * @return ProxyInterface
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    public function stub(array $methodsWithValues): ProxyInterface
    {
        /** @var $this ProxyInterface */
        $this->mockingStrategy->decorate($this->mock, $methodsWithValues);

        return $this;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    protected function doCall(string $name, array $arguments)
    {
        if ($this->mockingStrategy instanceof MockingStrategyInterface) {
            return $this->mockingStrategy->call($this->mock, $name, $arguments);
        }

        return null;
    }
}
