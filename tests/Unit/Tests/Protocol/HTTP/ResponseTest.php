<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\{Response, Response\Code};

/**
 * This file defines test class for HTTP Response.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ResponseTest extends TestCase {

    /**
     * Test for method code.
     *
     * @return void
     */
    public function testCode() {
        $response = new Response();

        $this->assertEquals(Code::OK_200, $response->code());
        $this->assertEquals(Code::CONFLICT_409, $response->code(Code::CONFLICT_409));
        $this->assertEquals(Code::CONFLICT_409, $response->code());

        $this->expectException(\TypeError::class);
        $response->code([]);
    }

    /**
     * Test for method headers.
     *
     * @return void
     */
    public function testHeaders() {
        $response = new Response();

        $this->assertNull($response->headers());
        $this->assertEquals(['val'], $response->headers(['val']));
        $this->assertEquals(['val'], $response->headers());

        $this->expectException(\TypeError::class);
        $response->headers('');
    }

    /**
     * Test for method httpVersion.
     *
     * @return void
     */
    public function testHttpVersion() {
        $response = new Response();

        $this->assertNull($response->httpVersion());
        $this->assertEquals('HTTP 2.0', $response->httpVersion('HTTP 2.0'));
        $this->assertEquals('HTTP 2.0', $response->httpVersion());

        $this->expectException(\TypeError::class);
        $response->httpVersion([]);
    }

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