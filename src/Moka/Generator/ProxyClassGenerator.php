<?php
declare(strict_types=1);

namespace Moka\Generator;

use Moka\Proxy\ProxyInterface;

class ProxyClassGenerator
{
    const UNSAFE_METHODS = ['__call', '__construct', '__destruct', '__clone'];
//    const CALL_PARAMETERS = ['name', 'arguments'];

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

        foreach ($methods as $method) {
            if (!in_array($method->getName(), self::UNSAFE_METHODS, true)) {
                $methodsArray[] = ProxyMethodGenerator::generateMethodString($method);
            }

            if ($method->getName() === '__call') {
                $p = [];
                $callParameters = $method->getParameters();
                foreach ($callParameters as $callParameter) {
                    $p[$callParameter->getPosition()] = (string)$callParameter->getType();
                }
            }
        }

        $callName = $p[0] ?? '';
        $callArguments = $p[1] ?? '';

        return sprintf(
            self::$template,
            $classWillBeEtended,
            ProxyInterface::class,
            ProxyTrait::class,
            implode(PHP_EOL, $methodsArray),
            $callName,
            $callArguments
        );
    }
}
