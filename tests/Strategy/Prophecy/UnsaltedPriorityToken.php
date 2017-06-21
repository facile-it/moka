<?php
declare(strict_types=1);

namespace Tests\Strategy\Prophecy;

use Moka\Strategy\Prophecy\AbstractPriorityToken;

class UnsaltedPriorityToken extends AbstractPriorityToken
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
