<?php
declare(strict_types=1);

namespace Moka;

/**
 * Trait MokaCleanerTrait
 * @package Moka
 */
trait MokaCleanerTrait
{
    /**
     * @return void
     */
    public function tearDown()
    {
        Moka::clean();
    }
}
