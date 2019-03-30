<?php
declare(strict_types=1);

namespace Tests\Plugin\Prophecy\Token;

use Moka\Plugin\Prophecy\Token\AbstractPriorityToken;

class UnsaltedPriorityToken extends AbstractPriorityToken
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        // No call to parent::__construct().
    }

    protected function getPriority(): int
    {
        return 0;
    }
}
