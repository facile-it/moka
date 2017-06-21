<?php
declare(strict_types=1);

namespace Moka\Plugin;

use Moka\Exception\NotImplementedException;

final class PluginHelper
{
    /**
     * The template namespace with plugin must be declared
     */
    const PLUGIN_NAMESPACE_TEMPLATE = 'Moka\\Plugin\\%s\\%sPlugin';

    /**
     * @param string $pluginName
     * @return string
     * @throws NotImplementedException
     */
    public static function loadPlugin(string $pluginName): string
    {
        $pluginFQCN = PluginHelper::generatePluginFQCN($pluginName);

        if (!class_exists($pluginFQCN) || !in_array(PluginInterface::class, class_implements($pluginFQCN), true)) {
            throw new NotImplementedException(
                sprintf(
                    'Mocking strategy "%s" does not exist',
                    $pluginName
                )
            );
        }

        return $pluginFQCN;
    }

    /**
     * @param string $pluginName
     * @return string
     */
    private static function generatePluginFQCN(string $pluginName): string
    {
        return sprintf(
            self::PLUGIN_NAMESPACE_TEMPLATE,
            ucfirst($pluginName),
            ucfirst($pluginName)
        );
    }
}
