<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP\Response;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\{Request, Response, Response\Builder, Response\Code};
use uSIreF\Networking\Protocol\Exception;

/**
 * This file defines test class for HTTP Response builder.
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
        $builder  = new Builder();
        $response = $this->createMock(Response::class);
        $response->expects($this->exactly(2))
            ->method('data')
            ->willReturn(null, []);
        $builder->response($response);

        $this->assertFalse($builder->isReadCompleted());
        $this->assertTrue($builder->isReadCompleted());
    }

    /**
     * Success test for method isWriteCompleted.
     *
     * @return void
     */
    public function testIsWriteCompleted() {
        $builder  = new Builder();
        $response = $this->createMock(Response::class);
        $message  = ['message' => 'message'];
        $response->expects($this->exactly(4))->method('data')->willReturn(null, null, $message, $message);
        $response->expects($this->exactly(1))->method('headers')->willReturn([]);
        $response->expects($this->exactly(1))->method('code')->willReturn(Code::OK_200);

        $builder->response($response);

        $this->assertFalse($builder->isWriteCompleted());
        $builder->written(10);
        $this->assertFalse($builder->isWriteCompleted());
        $builder->written(44);
        $this->assertTrue($builder->isWriteCompleted());
    }

    /**
     * Success test for method render.
     *
     * @return void
     */
    public function testRender() {
        $builder  = new Builder();
        $response = $this->createMock(Response::class);
        $message = ['message' => 'this is a message'];
        $response->expects($this->any())->method('data')->willReturn(null, $message, $message);
        $response->expects($this->any())->method('headers')->willReturn([]);
        $response->expects($this->any())->method('code')->willReturn(Code::OK_200);

        $builder->response($response);

        $this->assertEquals("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n", $builder->render());
        $this->assertEquals("HTTP/1.1 200 OK\r\nContent-Length: 25\r\n\r\nmessage=this+is+a+message", $builder->render());
        $builder->written(10);
        $this->assertEquals("00 OK\r\nContent-Length: 25\r\n\r\nmessage=this+is+a+message", $builder->render());
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

        $this->expectException(Exception::class);
        $builder->request($request);
    }

    /**
     * Fail test for method response.
     *
     * @return void
     */
    public function testResponse() {
        $builder  = new Builder();
        $response = $this->createMock(Response::class);

        $this->assertNull($builder->response());
        $this->assertInstanceOf(Response::class, $builder->response($response));
        $this->assertNull($builder->response());
    }

    /**
     * Success test for method code.
     *
     * @return void
     */
    public function testCode() {
        $builder  = new Builder();
        $response = new Response();

        $builder->response($response);

        $this->assertNotNull($builder->code());
        $this->assertEquals(Code::BAD_REQUEST_400, $builder->code(Code::BAD_REQUEST_400));
        $this->assertEquals(Code::BAD_REQUEST_400, $builder->code());
    }

    /**
     * Success test for method headers.
     *
     * @return void
     */
    public function testHeaders() {
        $builder  = new Builder();
        $response = new Response();

        $builder->response($response);

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
        $builder  = new Builder();
        $response = new Response();

        $builder->response($response);

        $this->assertNull($builder->data());
        $this->assertEquals([], $builder->data([]));
        $this->assertEquals([], $builder->data());
        $this->assertEquals(['val'], $builder->data(['val']));
        $this->assertEquals(['val', 'test'], $builder->data(['val', 'test']));
    }

}