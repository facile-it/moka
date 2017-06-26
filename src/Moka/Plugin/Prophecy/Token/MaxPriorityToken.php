<?php

declare(strict_types=1);

namespace Moka\Plugin\Prophecy\Token;

use Prophecy\Argument\ArgumentsWildcard;

/**
 * Class MaxPriorityToken
 * @package Moka\Plugin\Prophecy\Token
 */
class MaxPriorityToken extends AbstractPriorityToken
{
    /**
     * @var int
     */
    private static $priority = PHP_INT_MAX;

    /**
     * @return int
     */
    protected function getPriority(): int
    {
        /**
         * Ensure that this matcher overrides any Prophecy token, but still cannot be overridden by subsequent
         * Proxy::stub() calls.
         *
         * @see ArgumentsWildcard::scoreArguments()
         */
        return self::$priority--;
    }
}
