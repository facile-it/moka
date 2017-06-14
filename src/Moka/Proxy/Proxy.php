<?php
declare(strict_types=1);

namespace Moka;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

class Proxy
{
    private $mock;

    public function __construct(MockObject $mock)
    {
        $this->mock = $mock;
    }

    public function serve(): MockObject
    {
        return $this->mock;
    }

    public function stub(array $methodsWithValues): self
    {
        foreach ($methodsWithValues as $methodName => $methodValue) {
            $this->addMethod($methodName, $methodValue);
        }

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
}
