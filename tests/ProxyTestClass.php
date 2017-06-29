<?php
declare(strict_types=1);

namespace Tests;

use Moka\Generator\ProxyTrait;
use Moka\Proxy\ProxyInterface;

class ProxyTestClass implements ProxyInterface
{
    use ProxyTrait;

    public function __call(string $name, array $arguments)
    {
        return $this->doCall($name, $arguments);
    }
}
