<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

use Moka\Exception\InvalidArgumentException;

/**
 * @param string $name
 * @return bool
 *
 * @throws InvalidArgumentException
 */
function isPropertyName(string $name): bool
{
    return isName(
        $name,
        'property',
        stripName($name, ['static'])
    );
}
