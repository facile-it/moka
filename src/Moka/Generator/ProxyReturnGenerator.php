<?php
declare(strict_types=1);

namespace Moka\Generator;


class ProxyReturnGenerator
{
    public function generateMethodReturnType(\ReflectionType $type, \ReflectionMethod $method)
    {
        $allowNull = '';
        if ($type->allowsNull()) {
            $allowNull = '?';
        }

        $returnType = $this->getType($type);
        if ($returnType === 'self') {
            $returnType = $method->getDeclaringClass()->getName();
        }

        return sprintf(
            ':%s%s',
            $allowNull,
            $returnType
        );
    }

    public function getType(\ReflectionType $type)
    {
        return (string)$type;
    }
}
