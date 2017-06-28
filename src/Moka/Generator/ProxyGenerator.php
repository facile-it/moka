<?php
declare(strict_types=1);

namespace Moka\Generator;


use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;

class ProxyGenerator
{
    private $mockingStrategy;

    public function __construct(MockingStrategyInterface $mockingStrategy)
    {
        $this->mockingStrategy = $mockingStrategy;
    }

    final public function generate($fqcn): ProxyInterface
    {
        $object = $this->mockingStrategy->build($fqcn);
        $codeWillBeEvaluated = ProxyClassGenerator::generateCode(get_class($object));

        /** @var ProxyInterface|ProxyTrait $proxy */
        $proxy = eval($codeWillBeEvaluated);
        $proxy->_moka_setObject($object);
        $proxy->_moka_setMockingStrategy($this->mockingStrategy);

        return $proxy;
    }
}
