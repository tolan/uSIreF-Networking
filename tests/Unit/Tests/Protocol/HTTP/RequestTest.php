<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\{Request, Request\Method};

/**
 * This file defines test class for HTTP Request.
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
     * Test for method method.
     *
     * @return void
     */
    public function testMethod() {
        $request = new Request();

        $this->assertNull($request->method());
        $this->assertEquals(Method::GET, $request->method(Method::GET));
        $this->assertEquals(Method::GET, $request->method());

        $this->expectException(\TypeError::class);
        $request->method([]);
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

    /**
     * Test for method uri.
     *
     * @return void
     */
    public function testUri() {
        $request = new Request();

        $this->assertNull($request->uri());
        $this->assertEquals('uri', $request->uri('uri'));
        $this->assertEquals('uri', $request->uri());

        $this->expectException(\TypeError::class);
        $request->uri([]);
    }

    /**
     * Test for method httpVersion.
     *
     * @return void
     */
    public function testHttpVersion() {
        $request = new Request();

        $this->assertNull($request->httpVersion());
        $this->assertEquals('HTTP 2.0', $request->httpVersion('HTTP 2.0'));
        $this->assertEquals('HTTP 2.0', $request->httpVersion());

        $this->expectException(\TypeError::class);
        $request->httpVersion([]);
    }

    /**
     * Test for method query.
     *
     * @return void
     */
    public function testQuery() {
        $request = new Request();

        $this->assertNull($request->query());
        $this->assertEquals(['val'], $request->query(['val']));
        $this->assertEquals(['val'], $request->query());

        $this->expectException(\TypeError::class);
        $request->query('');
    }

    /**
     * Test for method headers.
     *
     * @return void
     */
    public function testHeaders() {
        $request = new Request();

        $this->assertNull($request->headers());
        $this->assertEquals(['val'], $request->headers(['val']));
        $this->assertEquals(['val'], $request->headers());

        $this->expectException(\TypeError::class);
        $request->headers('');
    }

}