<?php

declare(strict_types=1);

namespace Moka\Plugin\Phake;

use Moka\Exception\MissingDependencyException;
use Moka\Plugin\PluginInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class PhakePlugin
 * @package Moka\Plugin\Phake
 */
class PhakePlugin implements PluginInterface
{
    /**
     * @return MockingStrategyInterface
     *
     * @throws MissingDependencyException
     */
    public static function getStrategy(): MockingStrategyInterface
    {
        return new PhakeMockingStrategy();
    }
}
