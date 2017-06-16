<?php
declare(strict_types=1);

namespace Moka\Strategy\Prophecy;

/**
 * Class LowPriorityToken
 * @package Moka\Strategy\Prophecy
 */
class LowPriorityToken extends AbstractPriorityToken
{
    /**
     * @param $argument
     * @return int
     */
    public function scoreArgument($argument)
    {
        return 3;
    }
}
