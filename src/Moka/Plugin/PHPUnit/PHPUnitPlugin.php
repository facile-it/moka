<?php
declare(strict_types=1);

namespace Moka\Plugin\PHPUnit;

use Moka\Exception\PluginNotRegisteredException;
use Moka\Moka;
use Moka\Plugin\PluginInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class PHPUnitPlugin
 * @package Moka\Plugin\PHPUnit
 */
class PHPUnitPlugin implements PluginInterface
{
    /**
     * @return MockingStrategyInterface
     */
    public static function getStrategy(): MockingStrategyInterface
    {
        return new PHPUnitMockingStrategy();
    }
}
