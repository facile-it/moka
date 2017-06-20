<?php
declare(strict_types=1);

namespace Moka\Plugin\PHPUnit;

use Moka\Exception\PluginNotRegisteredException;
use Moka\Moka;
use Moka\Plugin\PluginInterface;

class PHPUnitPlugin implements PluginInterface
{
    public static function registerPlugin()
    {
        Moka::registerStrategy('phpunit', new PHPUnitMockingStrategy());
    }
}
