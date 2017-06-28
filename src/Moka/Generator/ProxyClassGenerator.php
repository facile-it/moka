<?php
declare(strict_types=1);

namespace Moka\Generator;

use Moka\Proxy\ProxyInterface;

class ProxyClassGenerator
{
    const UNSAFE_METHODS = ['__call', '__construct', '__destruct', '__clone'];

    private static $template = '
    return new class extends %s implements %s
    {
        use %s;
        %s
        
        public function __call(%s $name, %s $arguments)
        {
            return $this->doCall($name, $arguments);
        }
    };
    ';

    public static function generateCode(string $classWillBeEtended): string
    {
        $reflection = new \ReflectionClass($classWillBeEtended);
        $methods = $reflection->getMethods();
        $methodsArray = [];
        $p = array_fill(0, 2, '');

        foreach ($methods as $method) {
            if (!$method->isFinal() && !in_array($method->getName(), self::UNSAFE_METHODS, true)) {
                $methodsArray[] = ProxyMethodGenerator::generateMethodString($method);
            }

            if ($method->getName() === '__call') {
                $callParameters = $method->getParameters();
                foreach ($callParameters as $callParameter) {
                    $p[$callParameter->getPosition()] = (string)$callParameter->getType();
                }
            }
        }

        list($callName, $callArguments) = $p;

        return sprintf(
            self::$template,
            $classWillBeEtended,
            ProxyInterface::class,
            ProxyTrait::class,
            implode(PHP_EOL, $methodsArray),
            $callName?? '',
            $callArguments?? ''
        );
    }
}
