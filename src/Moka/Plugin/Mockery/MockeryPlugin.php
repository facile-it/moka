<?php
declare(strict_types=1);

namespace Moka\Plugin\Mockery;

use Moka\Plugin\PluginInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class MockeryPlugin
 * @package Moka\Plugin\Mockery
 */
class MockeryPlugin implements PluginInterface
{
    /**
     * @return MockingStrategyInterface
     */
    public static function getStrategy(): MockingStrategyInterface
    {
        return new MockeryMockingStrategy();
    }
}
