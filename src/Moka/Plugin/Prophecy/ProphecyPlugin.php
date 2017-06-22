<?php
declare(strict_types=1);

namespace Moka\Plugin\Prophecy;

use Moka\Plugin\PluginInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class ProphecyPlugin
 * @package Moka\Plugin\Prophecy
 */
class ProphecyPlugin implements PluginInterface
{
    /**
     * @return MockingStrategyInterface
     */
    public static function getStrategy(): MockingStrategyInterface
    {
        return new ProphecyMockingStrategy();
    }
}
