<?php
declare(strict_types=1);

namespace Moka\Plugin\Prophecy;

use Moka\Exception\PluginNotRegisteredException;
use Moka\Plugin\PluginInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class ProphecyPlugin
 * @package Moka\Plugin\Prophecy
 */
class ProphecyPlugin implements PluginInterface
{
    public static function check()
    {
        // TODO: Implement check() method.
    }

    /**
     * @return MockingStrategyInterface
     */
    public static function getStrategy(): MockingStrategyInterface
    {
        return new ProphecyMockingStrategy();
    }
}
