<?php
declare(strict_types=1);

namespace Moka\Plugin;

use Moka\Exception\MissingDependencyException;
use Moka\Exception\NotImplementedException;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class PluginHelper
 * @package Moka\Plugin
 */
final class PluginHelper
{
    /**
     * Plugin FQCN has to match this template.
     */
    const PLUGIN_FQCN_TEMPLATE = 'Moka\\Plugin\\%s\\%sPlugin';

    /**
     * @param string $pluginName
     * @return MockingStrategyInterface
     *
     * @throws NotImplementedException
     * @throws MissingDependencyException
     */
    public static function load(string $pluginName): MockingStrategyInterface
    {
        $pluginFQCN = self::generateFQCN($pluginName);

        if (
            !class_exists($pluginFQCN) ||
            !in_array(PluginInterface::class, class_implements($pluginFQCN), true)
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

    /**
     * @param string $pluginName
     * @return string
     */
    private static function generateFQCN(string $pluginName): string
    {
        return sprintf(
            self::PLUGIN_FQCN_TEMPLATE,
            ucfirst($pluginName),
            ucfirst($pluginName)
        );
    }
}
