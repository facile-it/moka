<?php
declare(strict_types=1);

namespace Moka\Generator;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;

trait ProxyTrait
{
    private $object;

    /**
     * @var MockingStrategyInterface
     */
    private $mockingStrategy;

    public function __construct()
    {
    }

    public function __call($name, array $arguments)
    {
        if ($this->mockingStrategy instanceof MockingStrategyInterface) {
            return $this->mockingStrategy->call($this->object, $name, $arguments);
        }
    }

    /**
     * @param mixed $object
     */
    public function _moka_setObject($object)
    {
        $this->object = $object;
    }

    public function _moka_setMockingStrategy(MockingStrategyInterface $mockingStrategy)
    {
        $this->mockingStrategy = $mockingStrategy;
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
        $this->mockingStrategy->decorate($this->object, $methodsWithValues);

        return $this;
    }

    /**
     * @return object
     *
     * @throws MockNotServedException
     */
    public function serve()
    {
        return $this->object;
    }
}
