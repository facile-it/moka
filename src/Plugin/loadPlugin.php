<?php
declare(strict_types=1);

namespace Moka\Plugin;

use Moka\Exception\MissingDependencyException;
use Moka\Exception\NotImplementedException;
use Moka\Strategy\MockingStrategyInterface;

/**
 * @param string $pluginName
 * @return MockingStrategyInterface
 *
 * @throws NotImplementedException
 * @throws MissingDependencyException
 */
function loadPlugin(string $pluginName): MockingStrategyInterface
{
    $pluginFQCN = generatePluginFQCN($pluginName);

    if (
        !class_exists($pluginFQCN) ||
        !\in_array(PluginInterface::class, class_implements($pluginFQCN), true)
    ) {
        throw new NotImplementedException(
            sprintf(
                'Mocking strategy "%s" does not exist',
                $pluginName
            )
        );
    }

    /** @var PluginInterface $pluginFQCN */
    return $pluginFQCN::getStrategy();
}
