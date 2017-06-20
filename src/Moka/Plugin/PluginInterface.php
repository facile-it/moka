<?php
declare(strict_types=1);

namespace Moka\Plugin;

use Moka\Exception\PluginNotRegisteredException;

interface PluginInterface
{
    /**
     * @return void
     *
     * @throws PluginNotRegisteredException
     */
    public static function registerPlugin();
}
