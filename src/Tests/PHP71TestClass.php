<?php
declare(strict_types=1);

namespace Moka\Tests;

class PHP71TestClass extends AbstractTestClass
{
    public function getSelfNullable(): ?self
    {
        return $this;
    }

    public function doNothing(): void
    {
    }
}
