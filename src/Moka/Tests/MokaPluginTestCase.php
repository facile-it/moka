<?php
declare(strict_types=1);

namespace Moka\Tests;

use Moka\Plugin\PluginInterface;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;

abstract class MokaPluginTestCase extends TestCase
{
    /**
     * @var PluginInterface
     */
    protected $pluginFQCN;

    final public function testGetStrategy()
    {
        $this->assertInstanceOf(MockingStrategyInterface::class, $this->pluginFQCN::getStrategy());
    }

    final protected function setPluginFQCN(string $pluginFQCN)
    {
        $this->pluginFQCN = $pluginFQCN;
    }
}
