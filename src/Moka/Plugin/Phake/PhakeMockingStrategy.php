<?php
declare(strict_types=1);

namespace Moka\Plugin\Phake;

use Moka\Exception\MissingDependencyException;
use Moka\Plugin\Phake\Matcher\FirstStubMatcher;
use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\MethodStub;
use Phake;
use Phake_IMock as PhakeMock;
use Phake_Proxies_AnswerBinderProxy as AnswerBinderProxy;

/**
 * Class PhakeMockingStrategy
 * @package Moka\Plugin\Phake
 */
class PhakeMockingStrategy extends AbstractMockingStrategy
{
    const CLASS_NAME = Phake::class;
    const PACKAGE_NAME = 'phake/phake';

    /**
     * PhakeMockingStrategy constructor.
     *
     * @throws MissingDependencyException
     */
    public function __construct()
    {
        self::checkDependencies(self::CLASS_NAME, self::PACKAGE_NAME);

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
     * @param MethodStub $stub
     * @return void
     */
    protected function doDecorateWithMethod($mock, MethodStub $stub)
    {
        $methodName = $stub->getName();
        $methodValue = $stub->getValue();

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
