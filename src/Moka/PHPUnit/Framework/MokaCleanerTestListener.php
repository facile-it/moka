<?php
declare(strict_types=1);

namespace Moka\PHPUnit\Framework;

use Moka\Moka;
use PHPUnit\Framework\BaseTestListener;

/**
 * Class MokaCleanerTestListener
 * @package Moka\PHPUnit\Framework
 */
class MokaCleanerTestListener extends BaseTestListener
{

    /**
     * @param \PHPUnit_Framework_Test $test
     * @param $time
     */
    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        Moka::clean();
    }
}
