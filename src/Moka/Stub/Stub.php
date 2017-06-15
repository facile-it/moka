<?php
declare(strict_types=1);

namespace Moka\Stub;

class Stub
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var mixed
     */
    private $methodValue;

    /**
     * Stub constructor.
     * @param string $methodName
     * @param mixed $methodValue
     */
    public function __construct(string $methodName, $methodValue)
    {
        $this->methodName = $methodName;
        $this->methodValue = $methodValue;
    }


    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return mixed
     */
    public function getMethodValue()
    {
        return $this->methodValue;
    }
}
