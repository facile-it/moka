<?php
declare(strict_types=1);

namespace Moka\Plugin\Prophecy;

use Moka\Moka;
use Moka\Plugin\PluginInterface;

class ProphecyPlugin implements PluginInterface
{
    public static function registerPlugin()
    {
        Moka::registerStrategy('prophecy', new ProphecyMockingStrategy());
    }
}
