<?php
declare(strict_types=1);

namespace Moka\Strategy\Prophecy;

use Prophecy\Argument\Token\TokenInterface;

abstract class AbstractPriorityToken implements TokenInterface
{
    /**
     * @return bool
     */
    public function isLast()
    {
        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
