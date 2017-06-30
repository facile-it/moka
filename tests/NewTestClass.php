<?php
declare(strict_types=1);

namespace Tests;

class NewTestClass extends AbstractTestClass
{
    public function getSelfNew(): ?self
    {
        return $this;
    }
}
