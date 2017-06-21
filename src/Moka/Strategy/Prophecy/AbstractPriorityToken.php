<?php
declare(strict_types=1);

namespace Moka\Strategy\Prophecy;

use Prophecy\Argument\Token\TokenInterface;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class AbstractPriorityToken
 * @package Moka\Strategy\Prophecy
 */
abstract class AbstractPriorityToken implements TokenInterface
{
    /**
     * @var int
     */
    private $salt;

    /**
     * MaxPriorityToken constructor.
     */
    public function __construct()
    {
        /**
         * Ensure this instance won't match any other token instance when serialized ($priority isn't enough, as the
         * serialization just checks for its value without calling getPriority()).
         *
         * @see ObjectProphecy::__call()
         */
        $this->salt = rand();
    }

    /**
     * @param $argument
     * @return int
     */
    public function scoreArgument($argument)
    {
        return $this->getPriority();
    }

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

    /**
     * @return int
     */
    abstract protected function getPriority(): int;
}
