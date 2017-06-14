<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 14/06/2017
 * Time: 15:02
 */

namespace Tests;


use Moka\Moka;
use Moka\Proxy\Proxy;

class MokaTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSuccess()
    {
        $this->assertInstanceOf(
            Proxy::class,
            Moka::get(\stdClass::class)
        );
    }

    public function testClean()
    {
        $proxy1 = Moka::get(\stdClass::class);
        Moka::clean();
        $proxy2 = Moka::get(\stdClass::class);

        $this->assertNotSame($proxy1, $proxy2);
    }
}
