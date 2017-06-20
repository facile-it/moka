<?php
declare(strict_types=1);

namespace Moka\Plugin\Mockery;

use Moka\Exception\PluginNotRegisteredException;
use Moka\Moka;
use Moka\Plugin\PluginInterface;

class MockeryPlugin implements PluginInterface
{
    public static function registerPlugin()
    {
        Moka::registerStrategy('mockery', new MockeryMockingStrategy());
    }
}
