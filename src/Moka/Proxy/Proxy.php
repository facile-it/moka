<?php
declare(strict_types=1);

namespace Moka\Proxy;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class Proxy
 * @package Moka\Proxy
 */
class Proxy implements ProxyInterface
{
    /**
     * @var MockObject
     */
    private $mock;

    /**
     * Proxy constructor.
     * @param MockObject $mock
     */
    public function __construct(MockObject $mock)
    {
        $this->mock = $mock;
    }

    /**
     * @return MockObject
     */
    public function serve(): MockObject
    {
        return $this->mock;
    }

    /**
     * @param array $methodsWithValues
     * @return ProxyInterface
     */
    public function stub(array $methodsWithValues): ProxyInterface
    {
        foreach ($methodsWithValues as $methodName => $methodValue) {
            $this->addMethod($methodName, $methodValue);
        }

        return $this;
    }

    /**
     * @param string $methodName
     * @param $methodValue
     */
    protected function addMethod(string $methodName, $methodValue)
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
}
