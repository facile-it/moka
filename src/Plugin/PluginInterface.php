<?php
declare(strict_types=1);

namespace Moka\Plugin;

use Moka\Exception\MissingDependencyException;
use Moka\Strategy\MockingStrategyInterface;

interface PluginInterface
{
    /**
     * @return MockingStrategyInterface
     *
     * @throws MissingDependencyException
     */
    public static function getStrategy(): MockingStrategyInterface;
}
