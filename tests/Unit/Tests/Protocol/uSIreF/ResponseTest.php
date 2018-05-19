<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\uSIreF;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\uSIreF\Response;

/**
 * This file defines test class for uSIreF Response.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ResponseTest extends TestCase {

    /**
     * Success test for method data.
     *
     * @return void
     */
    public function testData() {
        $response = new Response();

        $this->assertNull($response->data());
        $this->assertEquals('', $response->data(''));
        $this->assertEquals('', $response->data());
        $this->assertEquals([], $response->data([]));
        $this->assertEquals([], $response->data());
    }

}