<?php
declare(strict_types=1);

namespace Moka\Plugin\Phake\Matcher;

use Phake_Exception_MethodMatcherException as MethodMatcherException;
use Phake_Matchers_AbstractChainableArgumentMatcher as AbstractChainableArgumentMatcher;
use Phake_Matchers_IChainableArgumentMatcher;
use Phake_IMock as PhakeMock;

class FirstStubMatcher extends AbstractChainableArgumentMatcher
{
    /**
     * @var array
     */
    private static $methods = [];

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var bool
     */
    private $isValid = true;

    public function __construct(PhakeMock $mock, string $methodName)
    {
        $this->methodName = $methodName;

        $mockHash = spl_object_hash($mock);
        if (!isset(self::$methods[$mockHash])) {
            self::$methods[$mockHash] = [];
        }

        if (in_array($methodName, self::$methods[$mockHash])) {
            $this->isValid = false;
            return;
        }

        self::$methods[$mockHash][] = $methodName;
    }

    public function doArgumentsMatch(array &$arguments)
    {
        if (!$this->isValid) {
            throw new MethodMatcherException(
                sprintf(
                    'Cannot override definition for method "%s()"',
                    $this->methodName
                )
            );
        }
    }

    public function __toString()
    {
        return '';
    }

    public function setNextMatcher(Phake_Matchers_IChainableArgumentMatcher $nextMatcher)
    {
    }
}
