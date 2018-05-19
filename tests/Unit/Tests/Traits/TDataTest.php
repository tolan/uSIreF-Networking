<?php

namespace uSIreF\Networking\Unit\Tests\Traits;

use uSIreF\Networking\Unit\Abstracts\TestCase;

/**
 * This file defines test class for traits TData.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class TDataTest extends TestCase {

    /**
     * Success test for protected method handleData.
     *
     * @return void
     */
    public function testHandleData() {
        $tdata  = new TData();
        $method = $this->getTestableMethod(TData::class, 'handleData');

        $this->assertNull($method->invoke($tdata, 'test'));
        $this->assertEquals('val', $method->invoke($tdata, 'key'));
        $this->assertEquals('value', $method->invoke($tdata, 'test', 'value'));
        $this->assertEquals('value', $method->invoke($tdata, 'test'));
    }

    /**
     * Success test for method to.
     *
     * @return void
     */
    public function testTo() {
        $tdata = new TData();
        $answer = $tdata->to();

        $this->assertInternalType('array', $answer);
        $this->assertEquals(['key' => 'val'], $answer);
    }

    /**
     * Success test for method from.
     *
     * @return void
     */
    public function testFrom() {
        $tdata = new TData();

        $this->assertEquals(['key' => 'val'], $tdata->to());
        $tdata->from(['a' => 1, 'b' => 2]);
        $this->assertEquals(['a' => 1, 'b' => 2], $tdata->to());
    }

}