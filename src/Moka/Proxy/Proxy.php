<?php
declare(strict_types=1);

namespace Moka\Proxy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;
use Moka\Strategy\MockingStrategyInterface;

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
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     *
     * @throws MockNotCreatedException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->getMock()->$name(...$arguments);
    }

    /**
     * @return object
     *
     * @throws MockNotCreatedException
     */
    private function getMock()
    {
        if (!$this->mock) {
            $this->mock = $this->mockingStrategy->build($this->fqcn);
        }

        return $this->mock;
    }

    /**
     * @param array $methodsWithValues
     * @return Proxy
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    public function stub(array $methodsWithValues): self
    {
        $this->mockingStrategy->decorate($this->getMock(), $methodsWithValues);

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
