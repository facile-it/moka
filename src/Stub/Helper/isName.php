<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

use Moka\Exception\InvalidArgumentException;

/**
 * @param string $name
 * @param string $memberType
 * @param string $subject
 * @return bool
 * @throws InvalidArgumentException
 */
function isName(string $name, string $memberType, string $subject): bool
{
    validateName(stripName($name));

    return (bool)preg_match(
        sprintf(
            NAME_TEMPLATE,
            PREFIXES[$memberType]
        ),
        $subject
    );
}