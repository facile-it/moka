<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 12/06/2017
 * Time: 18:08
 */

namespace Moka;

/**
 * Class MockProxy
 * @package Phantom\PhantomBundle\Test\Mock
 */
class Proxy
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mock;

    /**
     * MockProxy constructor.
     * @param \PHPUnit_Framework_MockObject_MockObject $mock
     */
    public function __construct(\PHPUnit_Framework_MockObject_MockObject $mock)
    {
        $this->mock = $mock;
    }

    public function pass(): \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->mock;
    }

    public function stub(array $methodsWithValues): self
    {
        $this->addMethods($methodsWithValues);

        return $this;
    }

    protected function addMethod($methodName, $methodValue): void
    {
        $partial = $this->mock->method($methodName);

        if ($methodValue instanceof \Exception) {
            $partial
                ->willThrowException($methodValue);
        } else {
            $partial
                ->willReturn($methodValue);
        }
    }

    protected function addMethods(array $methodsWithValues): void
    {
        foreach ($methodsWithValues as $methodName => $methodValue) {
            $this->addMethod($methodName, $methodValue);
        }
    }

}