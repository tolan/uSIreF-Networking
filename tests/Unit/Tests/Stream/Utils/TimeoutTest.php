<?php

namespace uSIreF\Networking\Unit\Tests\Stream\Utils;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Stream\Utils\Timeout;

/**
 * This file defines test class for stream utils.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class TimeoutTest extends TestCase {

    /**
     * Success test for static method toArray.
     *
     * @return void
     */
    public function testToArray() {
        $this->assertEquals([null, null], Timeout::toArray(null));
        $this->assertEquals([null, null], Timeout::toArray(0));
        $this->assertEquals([0, 1000], Timeout::toArray(1));
        $this->assertEquals([0, 1], Timeout::toArray(0.001));
        $this->assertEquals([0, 20050], Timeout::toArray(20.05));
        $this->assertEquals([5, 250000], Timeout::toArray(5250.00004));
    }

}