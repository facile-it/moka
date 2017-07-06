<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use Moka\Exception\InvalidArgumentException;

/**
 * Trait VisibilityTrait
 * @package Moka\Generator\Template
 */
trait VisibilityTrait
{
    /**
     * @param \Reflector $reflector
     * @return string
     */
    protected static function getVisibility(\Reflector $reflector): string
    {
        if (
            !$reflector instanceof \ReflectionMethod &&
            !$reflector instanceof \ReflectionProperty
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Reflector must be an instance of "%s" or "%s"',
                    \ReflectionMethod::class,
                    \ReflectionProperty::class
                )
            );
        }

        if ($reflector->isPublic()) {
            return 'public';
        }

        if ($reflector->isProtected()) {
            return 'protected';
        }

        if ($reflector->isPrivate()) {
            return 'private';
        }

        throw new \RuntimeException('Witness me!');
    }
}
