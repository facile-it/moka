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
    /**
     * @var object
     */
    private $mock;

    /**
     * @var MockingStrategyInterface
     */
    private $mockingStrategy;

    public function __construct()
    {
    }

    /**
     * @param mixed $object
     */
    public function _moka_setObject($object)
    {
        $this->mock = $object;
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
        $this->mockingStrategy->decorate($this->mock, $methodsWithValues);

        return $this;
    }

    /**
     * @return object
     *
     * @throws MockNotServedException
     *
     * @deprecated since v2.0.0
     */
    public function serve()
    {
        return $this->mock;
    }

    protected function doCall(string $name, array $arguments)
    {
        if ($this->mockingStrategy instanceof MockingStrategyInterface) {
            return $this->mockingStrategy->call($this->mock, $name, $arguments);
        }
    }
}
