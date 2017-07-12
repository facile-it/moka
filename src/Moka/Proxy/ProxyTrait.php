<?php
declare(strict_types=1);

namespace Moka\Proxy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Trait ProxyTrait
 * @package Moka\Proxy
 */
trait ProxyTrait
{
    /**
     * @var object
     */
    private $__moka_mock;

    /**
     * @var MockingStrategyInterface
     */
    private $__moka_mockingStrategy;

    /**
     * @return object
     */
    public function __moka_getMock()
    {
        return $this->__moka_mock;
    }

    /**
     * @param object $mock
     * @return ProxyInterface|ProxyTrait
     */
    public function __moka_setMock($mock): self
    {
        $this->__moka_mock = $mock;

        return $this;
    }

    /**
     * @param MockingStrategyInterface $mockingStrategy
     * @return ProxyInterface|ProxyTrait
     */
    public function __moka_setMockingStrategy(MockingStrategyInterface $mockingStrategy): self
    {
        $this->__moka_mockingStrategy = $mockingStrategy;

        return $this;
    }

    /**
     * @param array $namesWithValues
     * @return ProxyInterface
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    public function stub(array $namesWithValues): ProxyInterface
    {
        /** @var $this ProxyInterface */
        $this->__moka_mockingStrategy->decorate($this->__moka_mock, $namesWithValues);

        return $this;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    protected function doCall(string $name, array $arguments)
    {
        if (!$this->__moka_mockingStrategy instanceof MockingStrategyInterface) {
            return null;
        }

        $target = $this->__moka_mockingStrategy->get($this->__moka_mock);

        return $target->$name(...$arguments);
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function doGet(string $name)
    {
        if (!$this->__moka_mockingStrategy instanceof MockingStrategyInterface) {
            return null;
        }

        return $this->__moka_mockingStrategy->call($this->__moka_mock, $name);
    }
}
