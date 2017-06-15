<?php
declare(strict_types=1);

namespace Moka\Proxy;

use Moka\Factory\StubFactory;
use Moka\Stub\Stub;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class Proxy
 * @package Moka\Proxy
 */
class Proxy
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
     * @return Proxy
     */
    public function stub(array $methodsWithValues): self
    {
        $stubSet = StubFactory::fromArray($methodsWithValues);
        foreach ($stubSet as $stub) {
            $this->addMethod($stub);
        }

        return $this;
    }

    /**
     * @param Stub $stub
     */
    protected function addMethod(Stub $stub)
    {
        $methodValue = $stub->getMethodValue();

        $partial = $this->mock->method($stub->getMethodName());

        if ($methodValue instanceof \Exception) {
            $partial
                ->willThrowException($methodValue);
        } else {
            $partial
                ->willReturn($methodValue);
        }
    }
}
