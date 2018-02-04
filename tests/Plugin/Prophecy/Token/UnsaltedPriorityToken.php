<?php
declare(strict_types=1);

namespace Tests\Plugin\Prophecy\Token;

use Moka\Plugin\Prophecy\Token\AbstractPriorityToken;

/**
 * Class UnsaltedPriorityToken
 * @package Tests\Plugin\Prophecy\Token
 */
class UnsaltedPriorityToken extends AbstractPriorityToken
{
    /**
     * UnsaltedPriorityToken constructor.
     */
    public function __construct()
    {
        // No call to parent::__construct().
    }

    /**
     * @return int
     */
    protected function getPriority(): int
    {
        return 0;
    }
}
