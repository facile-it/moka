<?php
declare(strict_types=1);

namespace Moka\Plugin\Phake\Matcher;

use Phake_Exception_MethodMatcherException as MethodMatcherException;
use Phake_IMock as PhakeMock;
use Phake_Matchers_AbstractChainableArgumentMatcher as AbstractChainableArgumentMatcher;
use Phake_Matchers_IChainableArgumentMatcher;

/**
 * Class FirstStubMatcher
 * @package Moka\Plugin\Phake\Matcher
 */
class FirstStubMatcher extends AbstractChainableArgumentMatcher
{
    /**
     * @var array
     */
    private static $stubsPerMock = [];

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var bool
     */
    private $isFirstStub = true;

    /**
     * FirstStubMatcher constructor.
     * @param PhakeMock $mock
     * @param string $methodName
     */
    public function __construct(PhakeMock $mock, string $methodName)
    {
        $this->methodName = $methodName;

        $mockHash = spl_object_hash($mock);
        if (!isset(self::$stubsPerMock[$mockHash])) {
            self::$stubsPerMock[$mockHash] = [];
        }

        if (\in_array($methodName, self::$stubsPerMock[$mockHash], $strict = true)) {
            $this->isFirstStub = false;
            return;
        }

        self::$stubsPerMock[$mockHash][] = $methodName;
    }

    /**
     * @param array $arguments
     *
     * @throws MethodMatcherException
     */
    public function doArgumentsMatch(array &$arguments): void
    {
        if (!$this->isFirstStub) {
            throw new MethodMatcherException(
                sprintf(
                    'Cannot override definition for method "%s()"',
                    $this->methodName
                )
            );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return \get_class($this);
    }

    /**
     * @param Phake_Matchers_IChainableArgumentMatcher $nextMatcher
     * @return void
     */
    public function setNextMatcher(Phake_Matchers_IChainableArgumentMatcher $nextMatcher): void
    {
    }
}
