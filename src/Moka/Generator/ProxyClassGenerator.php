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
        }

        return sprintf(
            self::$template,
            $classWillBeEtended,
            ProxyInterface::class,
            ProxyTrait::class,
            implode(PHP_EOL, $methodsArray)
        );
    }
}
