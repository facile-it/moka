<?php
declare(strict_types=1);

namespace Moka\Plugin\PHPUnit;

use Moka\Exception\PluginNotRegisteredException;
use Moka\Plugin\PluginInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class PHPUnitPlugin
 * @package Moka\Plugin\PHPUnit
 */
class PHPUnitPlugin implements PluginInterface
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
        return new PHPUnitMockingStrategy();
    }
}
