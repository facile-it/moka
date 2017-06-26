<?php
declare(strict_types=1);

namespace Tests\Plugin\PHPUnit;

use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
use Moka\Plugin\PHPUnit\PHPUnitPlugin;
use PHPUnit\Framework\TestCase;

class PHPUnitPluginTest extends TestCase
{
    /**
     * @var PHPUnitPlugin
     */
    private $phpUnitPlugin;

    protected function setUp()
    {
        $this->phpUnitPlugin = new PHPUnitPlugin();
    }

    public function testGetStrategy()
    {
        $this->assertInstanceOf(PHPUnitMockingStrategy::class, $this->phpUnitPlugin::getStrategy());
    }
}
