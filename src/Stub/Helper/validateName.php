<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

use Moka\Exception\InvalidArgumentException;

/**
 * @param string $name
 * @param string|null $type
 * @return void
 *
 * @throws InvalidArgumentException
 */
function validateName(string $name, string $type = null): void
{
    $functionName = sprintf(
        '%s\is%sName',
        __NAMESPACE__,
        ucfirst((string)$type)
    );

    $isAPrefix = array_key_exists($type, PREFIXES);
    $nameIsValid = $isAPrefix
        ? $functionName($name)
        : preg_match(REGEX_NAME, $name);

    if (!$nameIsValid) {
        $message = $isAPrefix
            ? sprintf(
                'Name must be prefixed by "%s", "%s" given',
                stripcslashes(PREFIXES[$type]),
                $name
            )
            : sprintf(
                'Name must be a valid variable or function name, "%s" given',
                $name
            );

        throw new InvalidArgumentException($message);
    }
}
