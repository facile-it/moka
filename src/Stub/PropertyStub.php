<?php
declare(strict_types=1);

namespace Moka\Stub;

use Moka\Exception\InvalidArgumentException;
use function Moka\Stub\Helper\validatePropertyName;

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
    public function __construct(string $name, $value)
    {
        validatePropertyName($name);

        parent::__construct($name, $value);
    }
}
