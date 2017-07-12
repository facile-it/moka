<?php
declare(strict_types=1);

namespace Moka\Plugin\Prophecy;

use Prophecy\Argument\Token\TokenInterface;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class MethodProphecyHelper
 * @package Moka\Plugin\Prophecy
 */
class MethodProphecyHelper
{
    /**
     * @var ObjectProphecy
     */
    private $mock;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @param ObjectProphecy $mock
     * @param string $methodName
     */
    public function __construct(ObjectProphecy $mock, string $methodName)
    {
        $this->mock = $mock;
        $this->methodName = $methodName;
    }

    /**
     * @param TokenInterface|null $token
     * @return MethodProphecy
     */
    public function set(TokenInterface $token = null): MethodProphecy
    {
        return $this->mock->{$this->methodName}($token);
    }
}
