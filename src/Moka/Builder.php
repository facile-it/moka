<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 12/06/2017
 * Time: 11:14
 */

namespace Moka;

/**
 * Class MockBuilder
 * @package Phantom\PhantomBundle\Test\Mock
 */
class Builder
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var \PHPUnit_Framework_MockObject_Generator
     */
    protected $generator;

    /**
     * MockBuilder constructor.
     * @param \PHPUnit_Framework_MockObject_Generator $generator
     */
    public function __construct(\PHPUnit_Framework_MockObject_Generator $generator)
    {
        $this->generator = $generator;
        $this->resetContainer();
    }


    /**
     * @return void
     */
    public function resetContainer(): void
    {
        $this->container = new Container();
    }


    /**
     * @return \PHPUnit_Framework_MockObject_Generator
     */
    protected function getGenerator(): \PHPUnit_Framework_MockObject_Generator
    {
        return $this->generator;
    }


    /**
     * @param string $something
     * @param string|null $key
     * @return Proxy
     */
    public function mock(string $something, string $key = null): Proxy
    {
        $key = $key ?: $something;
        if (!$this->container->has($key)) {
            $mock = $this->getGenerator()->getMock(
                $something,
                [],
                [],
                '',
                false
            );

            $mock = ProxyFactory::getProxy($mock);

            $this->container->set($key, $mock);
        }

        return $this->container->get($key);
    }

}