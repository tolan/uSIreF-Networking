<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP\Request;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\{Request, Response, Request\Builder};
use uSIreF\Networking\Protocol\Exception;

/**
 * This file defines test class for HTTP Request builder.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class BuilderTest extends TestCase {

    /**
     * Success test for method isReadCompleted.
     *
     * @return void
     */
    public function testIsReadCompleted() {
        $builder = new Builder();
        $request = $this->createMock(Request::class);
        $request->expects($this->exactly(2))
            ->method('data')
            ->willReturn(null, []);
        $builder->request($request);

        $this->assertFalse($builder->isReadCompleted());
        $this->assertTrue($builder->isReadCompleted());
    }

    /**
     * Success test for method isWriteCompleted.
     *
     * @return void
     */
    public function testIsWriteCompleted() {
        $builder = new Builder();
        $request = $this->createMock(Request::class);
        $message = ['message' => 'message'];
        $request->expects($this->exactly(4))->method('data')->willReturn(null, null, $message, $message);
        $request->expects($this->exactly(1))->method('method')->willReturn('POST');
        $request->expects($this->exactly(1))->method('uri')->willReturn('uri');
        $request->expects($this->exactly(1))->method('query')->willReturn([]);
        $request->expects($this->exactly(1))->method('requestUri')->willReturn('http://uri');

        $builder->request($request);

        $this->assertFalse($builder->isWriteCompleted());
        $builder->written(10);
        $this->assertFalse($builder->isWriteCompleted());
        $builder->written(38);
        $this->assertTrue($builder->isWriteCompleted());
    }

    /**
     * Success test for method render.
     *
     * @return void
     */
    public function testRender() {
        $builder = new Builder();
        $request = $this->createMock(Request::class);
        $message = ['message' => 'this is a message'];
        $request->expects($this->any())->method('data')->willReturn(null, $message, $message);
        $request->expects($this->any())->method('method')->willReturn('POST');
        $request->expects($this->any())->method('uri')->willReturn('uri');
        $request->expects($this->any())->method('query')->willReturn([]);
        $request->expects($this->any())->method('requestUri')->willReturn('http://uri');

        $builder->request($request);

        $this->assertEquals("POST /uri HTTP/1.1\r\nHost: uri\r\n\r\n", $builder->render());
        $this->assertEquals("POST /uri HTTP/1.1\r\nHost: uri\r\n\r\nmessage=this+is+a+message", $builder->render());
        $builder->written(10);
        $this->assertEquals("HTTP/1.1\r\nHost: uri\r\n\r\nmessage=this+is+a+message", $builder->render());
    }

    /**
     * Success test for method written.
     *
     * @return void
     */
    public function testWritten() {
        $builder = new Builder();

        $this->assertEquals(0, $builder->written());
        $this->assertEquals(10, $builder->written(10));
        $this->assertEquals(15, $builder->written(5));
    }

    /**
     * Success test for method request.
     *
     * @return void
     */
    public function testRequest() {
        $builder = new Builder();
        $request = $this->createMock(Request::class);

        $this->assertNull($builder->request());
        $this->assertInstanceOf(Request::class, $builder->request($request));
        $this->assertNull($builder->request());
    }

    /**
     * Fail test for method response.
     *
     * @return void
     */
    public function testResponse() {
        $builder  = new Builder();
        $response = $this->createMock(Response::class);

        $this->expectException(Exception::class);
        $builder->response($response);
    }

    /**
     * Success test for method method.
     *
     * @return void
     */
    public function testMethod() {
        $builder = new Builder();
        $request = new Request();

        $builder->request($request);

        $this->assertNull($builder->method());
        $this->assertEquals('GET', $builder->method('GET'));
        $this->assertEquals('GET', $builder->method());
        $this->assertEquals('POST', $builder->method('POST'));
        $this->assertEquals('POST', $builder->method());
    }

    /**
     * Success test for method requestUri.
     *
     * @return void
     */
    public function testRequestUri() {
        $builder = new Builder();
        $request = new Request();

        $builder->request($request);

        $this->assertNull($builder->requestUri());
        $this->assertEquals('first', $builder->requestUri('first'));
        $this->assertEquals('first', $builder->requestUri());
        $this->assertEquals('second', $builder->requestUri('second'));
        $this->assertEquals('second', $builder->requestUri());
    }

    /**
     * Success test for method uri.
     *
     * @return void
     */
    public function testUri() {
        $builder = new Builder();
        $request = new Request();

        $builder->request($request);

        $this->assertNull($builder->uri());
        $this->assertEquals('first', $builder->uri('first'));
        $this->assertEquals('first', $builder->uri());
        $this->assertEquals('second', $builder->uri('second'));
        $this->assertEquals('second', $builder->uri());
    }

    /**
     * Success test for method query.
     *
     * @return void
     */
    public function testQuery() {
        $builder = new Builder();
        $request = new Request();

        $builder->request($request);

        $this->assertNull($builder->query());
        $this->assertEquals([], $builder->query([]));
        $this->assertEquals([], $builder->query());
        $this->assertEquals(['val'], $builder->query(['val']));
        $this->assertEquals(['val', 'test'], $builder->query(['val', 'test']));
    }

    /**
     * Success test for method headers.
     *
     * @return void
     */
    public function testHeaders() {
        $builder = new Builder();
        $request = new Request();

        $builder->request($request);

        $this->assertNull($builder->headers());
        $this->assertEquals([], $builder->headers([]));
        $this->assertEquals([], $builder->headers());
        $this->assertEquals(['val'], $builder->headers(['val']));
        $this->assertEquals(['val', 'test'], $builder->headers(['val', 'test']));
    }

    /**
     * Success test for method data.
     *
     * @return void
     */
    public function testData() {
        $builder = new Builder();
        $request = new Request();

        $builder->request($request);

        $this->assertNull($builder->data());
        $this->assertEquals([], $builder->data([]));
        $this->assertEquals([], $builder->data());
        $this->assertEquals(['val'], $builder->data(['val']));
        $this->assertEquals(['val', 'test'], $builder->data(['val', 'test']));
    }

}