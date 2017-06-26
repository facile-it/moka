<?php

declare(strict_types=1);

namespace Tests\Plugin\Prophecy\Token;

class UnsaltedPriorityToken extends \Moka\Plugin\Prophecy\Token\AbstractPriorityToken
{
    public function __construct()
    {
        // No call to parent::__construct().
    }

    protected function getPriority(): int
    {
        return 0;
    }
}
