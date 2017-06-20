<?php
declare(strict_types=1);

namespace Moka\Strategy\Prophecy;

/**
 * Class NoPriorityToken
 * @package Moka\Strategy\Prophecy
 */
class NoPriorityToken extends AbstractPriorityToken
{
    /**
     * @param $argument
     * @return int
     */
    public function scoreArgument($argument)
    {
        // Ensure this matcher is overridden by Prophecy tokens.
        return 2 - .1;
    }
}
