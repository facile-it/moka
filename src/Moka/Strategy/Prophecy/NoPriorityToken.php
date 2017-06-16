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
        return 2;
    }
}
