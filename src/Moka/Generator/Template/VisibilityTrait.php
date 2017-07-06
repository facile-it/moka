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

        foreach (['Public', 'Protected', 'Private'] as $visibility) {
            if ($reflector->{'is' . $visibility}()) {
                return strtolower($visibility);
            }
        }

        throw new \RuntimeException('Witness me!');
    }
}
