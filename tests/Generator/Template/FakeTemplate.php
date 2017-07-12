<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\VisibilityTrait;

class FakeTemplate
{
    use VisibilityTrait;

    public static function checkVisibility(\Reflector $reflector)
    {
        return static::getVisibility($reflector);
    }
}
