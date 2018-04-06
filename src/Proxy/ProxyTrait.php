<?php
declare(strict_types=1);

namespace Moka\Proxy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\MockingStrategyInterface;
use function Moka\Stub\Helper\isMethodName;
use function Moka\Stub\Helper\isPropertyName;
use function Moka\Stub\Helper\stripNameAndValidate;

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
     * @var array
     */
    private $__moka_properties = [];

    /**
     * @var array
     */
    private $__moka_methods = [];

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
        foreach ($namesWithValues as $name => $value) {
            if (isPropertyName($name)) {
                $this->__moka_properties[] = stripNameAndValidate($name);
            }

            if (isMethodName($name)) {
                $this->__moka_methods[] = stripNameAndValidate($name);
            }
        }

        /** @var $this ProxyInterface */
        $this->__moka_mockingStrategy->decorate($this->__moka_mock, $namesWithValues);

        return $this;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
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
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    protected function doGet(string $name)
    {
        if (!$this->__moka_mockingStrategy instanceof MockingStrategyInterface) {
            return null;
        }

        if (\in_array($name, $this->__moka_properties, $strict = false)) {
            return $this->__moka_mockingStrategy->get($this->__moka_mock)->$name;
        }

        return $this->__moka_mockingStrategy->call($this->__moka_mock, $name);
    }
}
