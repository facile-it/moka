<?php
declare(strict_types=1);

namespace Moka\Generator;

class ProxyReturnGenerator
{
    public static function generateMethodReturnType(\ReflectionType $type, \ReflectionMethod $method)
    {
        $allowNull = '';
        if ($type->allowsNull()) {
            $allowNull = '?';
        }

        $returnType = self::getType($type);
        if ($returnType === 'self') {
            $returnType = $method->getDeclaringClass()->getName();
        }

        return sprintf(
            ':%s%s',
            $allowNull,
            $returnType
        );
    }

    public static function getType(\ReflectionType $type)
    {
        return (string)$type;
    }
}
