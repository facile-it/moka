<?php
declare(strict_types=1);

namespace Moka\Generator;


class ProxyArgumentGenerator
{
    public static function generateMethodParameter(\ReflectionParameter $parameter)
    {
        try {
            $allowsNull = $parameter->allowsNull();
            $defaultValue = null;
            if ($allowsNull) {
                $defaultValue = 'null';
            } else {
                $defaultValue = var_export($parameter->getDefaultValue(), true);
            }

            if ($defaultValue) {
                $defaultValue = "= $defaultValue";
            }
        } catch (\ReflectionException $exception) {
            $defaultValue = '';
        }

        try {
            $type = $parameter->getType();
            if ($type) {
                $type = ' ' . self::getType($type) . ' ';
            } else {
                $type = '';
            }

            $name = '$' . $parameter->getName();

//            $canBePassByValue = $parameter->canBePassedByValue();
            $isPassedByReference = $parameter->isPassedByReference();
            $byReference = '';
            if ($isPassedByReference) {
                $byReference = ' &';
            }

            $isVariadic = $parameter->isVariadic();
            if ($isVariadic) {
                $type = '...';
                $byReference = '';
                $defaultValue = '';
            }


        } catch (\ReflectionException $e) {

        }

        return sprintf(
            '%s%s%s%s',
            $type,
            $byReference,
            $name,
            $defaultValue
        );
    }

    protected static function getType(\ReflectionType $type)
    {
        return (string)$type;
    }
}
