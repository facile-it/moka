<?php
declare(strict_types=1);

namespace Coffee;


use Moka\Proxy\ProxyInterface;
use Prophecy\Prophecy\ObjectProphecy;

trait ProxyTrait
{
    private $object;

    public function __call($name, array $arguments)
    {
        if ($this->serve() instanceof ObjectProphecy) {
            return $this->serve()->reveal()->$name(...$arguments);
        }

        return $this->serve()->$name(...$arguments);
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    public function stub(array $methodsWithValues): ProxyInterface
    {

    }

    public function serve()
    {
        return $this->object;
    }
}
