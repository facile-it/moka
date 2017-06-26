<?php
declare(strict_types=1);

namespace Tests\Plugin\Mockery;

use Moka\Plugin\Mockery\MockeryPlugin;
use Moka\Tests\MokaPluginTestCase;

class MockeryPluginTest extends MokaPluginTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setPluginFQCN(MockeryPlugin::class);
    }
}
