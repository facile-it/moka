<?php
declare(strict_types=1);

namespace Moka\Plugin\Phake;

use Moka\Plugin\Phake\Matcher\FirstStubMatcher;
use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\Stub;
use Phake;
use Phake_IMock as PhakeMock;
use Phake_Proxies_AnswerBinderProxy as AnswerBinderProxy;

/**
 * Class PhakeMockingStrategy
 * @package Moka\Plugin\Phake
 */
class PhakeMockingStrategy extends AbstractMockingStrategy
{
    /**
     * PhakeMockingStrategy constructor.
     */
    public function __construct()
    {
        $this->setMockType(PhakeMock::class);
    }

    /**
     * @param string $fqcn
     * @return PhakeMock
     */
    protected function doBuild(string $fqcn)
    {
        return Phake::mock($fqcn);
    }

    /**
     * @param PhakeMock $mock
     * @param Stub $stub
     * @return void
     */
    protected function doDecorate($mock, Stub $stub)
    {
        $methodName = $stub->getMethodName();
        $methodValue = $stub->getMethodValue();

        /** @var AnswerBinderProxy $partial */
        $partial = Phake::when($mock)->$methodName(new FirstStubMatcher($mock, $methodName));
        $methodValue instanceof \Throwable
            ? $partial->thenThrow($methodValue)
            : $partial->thenReturn($methodValue);
    }

    /**
     * @param PhakeMock $mock
     * @return PhakeMock
     */
    protected function doGet($mock)
    {
        return $mock;
    }
}
