<?php
declare(strict_types=1);

namespace Moka\Stub;

/**
 * Class Stub
 * @package Moka\Stub
 */
class Stub
{
    const PREFIX_PROPERTY = '$';

    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Stub constructor.
     * @param string $methodName
     * @param mixed $methodValue
     */
    public function __construct(string $methodName, $methodValue)
    {
        $this->name = $methodName;
        $this->value = $methodValue;
    }

    /**
     * @return bool
     */
    public function isProperty(): bool
    {
        return self::PREFIX_PROPERTY === $this->getName()[0];
    }

    /**
     * @return bool
     */
    public function isMethod(): bool
    {
        return !$this->isProperty();
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
