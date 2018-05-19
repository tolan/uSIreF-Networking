<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\uSIreF;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\uSIreF\Request;

/**
 * This file defines test class for uSIreF Request.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class RequestTest extends TestCase {

    /**
     * Success test for method data.
     *
     * @return void
     */
    public function testData() {
        $request = new Request();

        $this->assertNull($request->data());
        $this->assertEquals('', $request->data(''));
        $this->assertEquals('', $request->data());
        $this->assertEquals([], $request->data([]));
        $this->assertEquals([], $request->data());
    }

    /**
     * Test for method requestUri.
     *
     * @return void
     */
    public function testRequestUri() {
        $request = new Request();

        $this->assertNull($request->requestUri());
        $this->assertEquals('uri', $request->requestUri('uri'));
        $this->assertEquals('uri', $request->requestUri());

        $this->expectException(\TypeError::class);
        $request->requestUri([]);
    }

}