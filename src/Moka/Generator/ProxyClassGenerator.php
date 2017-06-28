<?php
declare(strict_types=1);

namespace Coffee\Generator;


use Coffee\ProxyTrait;
use Moka\Proxy\ProxyInterface;

class ProxyClassGenerator
{
    private $template = '
    class %s extends %s implements %s
    {
        use %s;
        %s
    };
    
    $instantiator = new \Doctrine\Instantiator\Instantiator();

    return $instantiator->instantiate("%s");
    ';

    private $methodGenerator;

    public function __construct()
    {
        $this->methodGenerator = new ProxyMethodGenerator();
    }

    public function generate($object): ProxyInterface
    {
        $objectFQCN = get_class($object);
        $codeWillBeEvalueted = $this->generateCode($objectFQCN);
        /** @var ProxyInterface|ProxyTrait $proxy */
        $proxy = eval($codeWillBeEvalueted);
        $proxy->setObject($object);

        return $proxy;
    }

    protected function generateCode(string $classWillBeEtended): string
    {

        $reflection = new \ReflectionClass($classWillBeEtended);
        $methods = $reflection->getMethods();
        $methodsArray = [];
        foreach ($methods as $method) {
            if ($method->getName() !== '__call') {
                $methodsArray[] = $this->methodGenerator->generateMethodString($method);
            }
        }

        $className = 'Cofffee' . '_' . mt_rand();

        return sprintf(
            $this->template,
            $className,
            $classWillBeEtended,
            ProxyInterface::class,
            ProxyTrait::class,
            implode(PHP_EOL, $methodsArray),
            $className
        );
    }
}
