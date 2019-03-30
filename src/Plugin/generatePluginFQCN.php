<?php
declare(strict_types=1);

namespace Moka\Plugin;

/**
 * Plugin FQCN has to match this template.
 */
const PLUGIN_FQCN_TEMPLATE = 'Moka\\Plugin\\%s\\%sPlugin';


function generatePluginFQCN(string $pluginName): string
{
    return sprintf(
        PLUGIN_FQCN_TEMPLATE,
        ucfirst($pluginName),
        ucfirst($pluginName)
    );
}
