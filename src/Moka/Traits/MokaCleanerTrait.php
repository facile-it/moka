<?php

declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;

/**
 * Trait MokaCleanerTrait
 * @package Moka\Traits
 */
trait MokaCleanerTrait
{
    /**
     * @return void
     */
    protected function tearDown()
    {
        Moka::clean();
    }
}
