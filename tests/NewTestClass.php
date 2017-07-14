<?php
declare(strict_types=1);

namespace Tests;

use Moka\Tests\AbstractTestClass;

class NewTestClass extends AbstractTestClass
{
    public function getSelfNew(): ?self
    {
        return $this;
    }
}
