<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 14/06/2017
 * Time: 13:56
 */

namespace Tests\Proxy;


class MockFakeClass
{
    public function isValid(): bool
    {
        return true;
    }

    public function throwException()
    {

    }
}
