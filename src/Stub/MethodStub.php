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
     * MethodStub constructor.
     * @param string $name
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, $value)
    {
        StubHelper::validateMethodName($name);

        parent::__construct($name, $value);
    }
}
