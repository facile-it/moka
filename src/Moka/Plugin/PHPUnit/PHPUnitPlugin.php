<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 20/06/2017
 * Time: 17:43
 */

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
