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
function isStaticName(string $name): bool
{
    return isName(
        $name,
        'static',
        $name
    );
}
