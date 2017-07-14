<?php
declare(strict_types=1);

namespace Moka\Tests;

class NewTestClass extends AbstractTestClass
{
    public function getSelfNew(): ?self
    {
        return $this;
    }

    public function doNothing(): void
    {
    }
}
