<?php
declare(strict_types=1);

namespace Moka\Stub;

use Moka\Exception\InvalidArgumentException;

/**
 * Class PropertyStub
 * @package Moka\Stub
 */
class PropertyStub extends AbstractStub
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
        if (static::PREFIX_PROPERTY !== $name[0]) {
            throw new InvalidArgumentException();
        }

        parent::__construct(substr($name, 1), $value);
    }
}
