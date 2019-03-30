<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;

class TestProxy implements ProxyInterface
{
    use ProxyTrait;

    public $isTrue;

    public static $getInt;

    public function __construct()
    {
        unset($this->isTrue);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->doCall($name, $arguments);
    }

    public function __get(string $name)
    {
        return $this->doGet($name);
    }

    public function stub(array $namesWithValues): ProxyInterface
    {
        $this->doStub($namesWithValues);

        return $this;
    }

}
