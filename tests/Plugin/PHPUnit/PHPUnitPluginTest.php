<?php
declare(strict_types=1);

namespace Tests\Plugin\PHPUnit;

use Moka\Plugin\PHPUnit\PHPUnitPlugin;
use Moka\Tests\MokaPluginTestCase;

class PHPUnitPluginTest extends MokaPluginTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setPluginFQCN(PHPUnitPlugin::class);
    }
}
