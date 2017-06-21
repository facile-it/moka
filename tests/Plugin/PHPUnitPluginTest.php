<?php
declare(strict_types=1);

namespace Tests\Plugin;

use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
use Moka\Plugin\PHPUnit\PHPUnitPlugin;
use PHPUnit\Framework\TestCase;

class PHPUnitPluginTest extends TestCase
{
    /**
     * @var PHPUnitPlugin
     */
    private $phpUnitPlugin;

    public function setUp()
    {
        $this->phpUnitPlugin = new PHPUnitPlugin();
    }

    public function testGetStrategy()
    {
        $this->assertInstanceOf(PHPUnitMockingStrategy::class, $this->phpUnitPlugin::getStrategy());
    }
}
