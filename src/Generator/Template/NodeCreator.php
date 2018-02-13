<?php
declare(strict_types=1);

namespace Moka\Generator\Template;
use PhpParser\Node;

/**
 * Interface NodeCreator
 */
interface NodeCreator
{
    /**
     * @param \Reflector $property
     * @return Node
     */
    public static function create(\Reflector $property): Node;
}
