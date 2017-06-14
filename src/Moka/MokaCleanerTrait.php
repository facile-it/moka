<?php
declare(strict_types=1);

namespace Moka;

use Moka\Factory\BuilderFactory;

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
        BuilderFactory::get()->clean();
    }
}
