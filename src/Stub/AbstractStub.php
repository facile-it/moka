<?php
declare(strict_types=1);

namespace Moka\Stub;

use Moka\Exception\InvalidArgumentException;
use function Moka\Stub\Helper\stripNameAndValidate;

/**
 * Class AbstractStub
 * @package Moka\Stub
 */
abstract class AbstractStub implements StubInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * AbstractStub constructor.
     * @param string $name
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, $value)
    {
        $this->name = stripNameAndValidate($name);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
