<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

/**
 * @param string $name
 * @param array|null $prefixes
 * @return string
 */
function doStripName(string $name, array $prefixes = null): string
{
    $prefixes = null !== $prefixes
        ? array_intersect(array_keys(PREFIXES), $prefixes)
        : array_keys(PREFIXES);

    return array_reduce($prefixes, function (string $name, string $prefix) {
        return preg_replace(
            sprintf('/^%s/', PREFIXES[$prefix]),
            '',
            $name
        );
    }, $name);
}