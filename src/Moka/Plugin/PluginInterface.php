<?php
declare(strict_types=1);

namespace Moka\Plugin;

use Moka\Strategy\MockingStrategyInterface;

interface PluginInterface
{
    /**
     * @return MockingStrategyInterface
     */
    public static function getStrategy(): MockingStrategyInterface;
}
