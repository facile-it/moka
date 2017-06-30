<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;

class FakeProxy implements ProxyInterface
{
    use ProxyTrait;

    public function __call(string $name, array $arguments)
    {
        return $this->doCall($name, $arguments);
    }
}
