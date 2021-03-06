<?php
declare(strict_types=1);

namespace Moka\Tests;

interface TestInterface
{
    public function isTrue(): bool;

    public function getInt(): int;

    public function getSelf(): TestInterface;

    public function throwException();
}
