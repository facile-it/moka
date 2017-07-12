<?php
declare(strict_types=1);

namespace Moka\Stub;

use Moka\Exception\InvalidArgumentException;

/**
 * Class MethodStub
 * @package Moka\Stub
 */
class MethodStub extends AbstractStub
{
    /**
     * PropertyStub constructor.
     * @param string $name
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct($name, $value)
    {
        if (static::PREFIX_PROPERTY === $name[0]) {
            throw new InvalidArgumentException();
        }

        parent::__construct($name, $value);
    }
}
