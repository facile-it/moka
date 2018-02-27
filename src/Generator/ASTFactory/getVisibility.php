<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use PhpParser\Node;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use RuntimeException;
use function get_class;
use function strtolower;

/**
 * @param Reflector $reflector
 * @return string
 * @throws InvalidArgumentException
 * @throws RuntimeException
 */
function getVisibility(Reflector $reflector): string
{
    if (
        !$reflector instanceof ReflectionMethod &&
        !$reflector instanceof ReflectionProperty
    ) {
        throw new InvalidArgumentException(
            sprintf(
                'Reflector must be an instance of "%s" or "%s", "%s" given',
                ReflectionMethod::class,
                ReflectionProperty::class,
                get_class($reflector)
            )
        );
    }

    foreach (['Public', 'Protected', 'Private'] as $visibility) {
        if ($reflector->{'is' . $visibility}()) {
            return strtolower($visibility);
        }
    }

    throw new RuntimeException('Witness me!');
}
