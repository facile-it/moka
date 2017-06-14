<?php
declare(strict_types=1);

namespace Moka;

use Moka\Exception\MockNotCreatedException;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;

class Builder
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var MockGenerator
     */
    protected $generator;

    public function __construct(MockGenerator $generator)
    {
        $this->generator = $generator;
        $this->clean();
    }

    public function clean(): void
    {
        $this->container = new Container();
    }

    public function getProxy(string $fqcn, string $key = null): Proxy
    {
        $key = $key ?: $fqcn;

        if (!$this->container->has($key)) {
            $this->container->set($key, $this->buildProxy($fqcn));
        }

        return $this->container->get($key);
    }

    protected function buildProxy(string $fqcn): Proxy
    {
        try {
            $mock = $this->getGenerator()->getMock(
                $fqcn,
                $methods = [],
                $arguments = [],
                $mockClassName = '',
                $callOriginalConstructor = false
            );
        } catch (\Exception $exception) {
            throw new MockNotCreatedException(sprintf('Unable to create a mock object for "$fqcn": %s', $fqcn));
        }

        return ProxyFactory::get($mock);
    }

    protected function getGenerator(): MockGenerator
    {
        return $this->generator;
    }
}
