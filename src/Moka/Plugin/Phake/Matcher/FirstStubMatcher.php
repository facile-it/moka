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
    private $method;

    /**
     * @var bool
     */
    private $isValid = true;

    public function __construct(PhakeMock $mock, string $method)
    {
        $this->method = $method;

        $hash = spl_object_hash($mock);
        if (!isset(self::$methods[$hash])) {
            self::$methods[$hash] = [];
        }

        if (in_array($method, self::$methods[$hash])) {
            $this->isValid = false;
            return;
        }

        self::$methods[$hash][] = $method;
    }

    public function doArgumentsMatch(array &$arguments)
    {
        if (!$this->isValid) {
            throw new MethodMatcherException(
                sprintf(
                    'Cannot override definition for method "%s()"',
                    $this->method
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
