<?php
declare(strict_types=1);

namespace Moka\Stub;

use Moka\Exception\InvalidArgumentException;

/**
 * Class StubHelper
 * @package Moka\Stub
 */
class StubHelper
{
    const PREFIXES = [
        'static' => '::',
        'property' => '\\$'
    ];

    /**
     * http://php.net/manual/en/language.variables.basics.php
     * http://php.net/manual/en/functions.user-defined.php
     */
    const REGEX_NAME = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

    /**
     * @param string $name
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function isStaticName(string $name): bool
    {
        self::validateName($name);

        return (bool)preg_match(
            sprintf(
                '/^%s/',
                static::PREFIXES['static']
            ),
            $name
        );
    }

    /**
     * @param string $name
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public static function validateStaticName(string $name)
    {
        self::validateName($name, 'static');
    }

    /**
     * @param string $name
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function isPropertyName(string $name): bool
    {
        self::validateName($name);

        return (bool)preg_match(
            sprintf(
                '/^(%s)?%s/',
                static::PREFIXES['static'],
                static::PREFIXES['property']
            ),
            $name
        );
    }

    /**
     * @param string $name
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public static function validatePropertyName(string $name)
    {
        self::validateName($name, 'property');
    }

    /**
     * @param string $name
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function stripName(string $name): string
    {
        self::validateName($name);

        return self::doStripName($name);
    }

    /**
     * @param string $name
     * @return string
     */
    private static function doStripName(string $name): string
    {
        return array_reduce(static::PREFIXES, function ($name, $prefix) {
            return preg_replace(
                sprintf(
                    '/^%s/',
                    $prefix
                ),
                '',
                $name
            );
        }, $name);
    }

    /**
     * @param string $name
     * @param string|null $type
     * @return void
     *
     * @throws InvalidArgumentException
     */
    private static function validateName(string $name, string $type = null)
    {
        $methodName = sprintf('is%sName', $type);
        $nameIsValid = isset(static::PREFIXES[$type])
            ? static::$methodName($name)
            : preg_match(static::REGEX_NAME, self::doStripName($name));

        if (!$nameIsValid) {
            $message = isset(static::PREFIXES[$type])
                ? sprintf(
                    'Name must be prefixed by "%s", "%s" given',
                    stripcslashes(static::PREFIXES[$type]),
                    $name
                )
                : sprintf(
                    'Name must be a valid variable or function name, "%s" given',
                    self::doStripName($name)
                );

            throw new InvalidArgumentException($message);
        }
    }
}
