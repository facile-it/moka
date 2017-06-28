<?php
declare(strict_types=1);

namespace Moka\Generator;


class ProxyMethodGenerator
{
    private static $template = '
        public %s function %s(%s)%s
        {
            %s$this->__call("%s", func_get_args());
        }
    ';

    public static function generateMethodString(\ReflectionMethod $method)
    {
        $static = $method->isStatic() ? 'static' : '';
        $originalReturnType = $method->getReturnType();

        $returnType = !$originalReturnType ? '' : ProxyReturnGenerator::generateMethodReturnType($originalReturnType, $method);

        $parameters = $method->getParameters();
        $arguments = [];
        if ($parameters) {
            foreach ($method->getParameters() as $parameter) {
                $arguments[] = ProxyArgumentGenerator::generateMethodParameter($parameter);
            }
        }

        $method->getReturnType();

        $returnStatement = 'return ';
        if (null !== $originalReturnType && self::getType($originalReturnType) === 'void') {
            $returnStatement = '';
        }

        return sprintf(
            self::$template,
            $static,
            $method->getName(),
            implode(', ', $arguments),
            $returnType,
            $returnStatement,
            $method->getName()
        );
    }

    protected static function getType(\ReflectionType $type)
    {
        return (string)$type;
    }

}
