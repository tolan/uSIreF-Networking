<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\{Builder, Request, Response, Response\Code};

/**
 * This file defines test class for HTTP Builder.
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
            ->willReturn(null, '');

        $builder->response($response);

        $this->assertFalse($builder->isReadCompleted());
        $this->assertTrue($builder->isReadCompleted());

        $this->expectException(\Error::class);
        (new Builder())->isReadCompleted();
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

        $this->expectException(\Error::class);
        (new Builder())->isWriteCompleted();
    }

    /**
     * Success test for method render.
     *
     * @return void
     */
    public function testRender() {
        $builder  = new Builder();
        $response = $this->createMock(Response::class);
        $message  = ['message' => 'this is a message'];
        $response->expects($this->any())->method('data')->willReturn(null, $message, $message);
        $response->expects($this->any())->method('headers')->willReturn([]);
        $response->expects($this->any())->method('code')->willReturn(Code::OK_200);

        $builder->response($response);

        $this->assertEquals("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n", $builder->render());
        $this->assertEquals("HTTP/1.1 200 OK\r\nContent-Length: 25\r\n\r\nmessage=this+is+a+message", $builder->render());
        $builder->written(10);
        $this->assertEquals("00 OK\r\nContent-Length: 25\r\n\r\nmessage=this+is+a+message", $builder->render());

        $this->expectException(\Error::class);
        (new Builder())->render();
    }

    /**
     * Success test for method written.
     *
     * @return void
     */
    public function testWritten() {
        $builder  = new Builder();
        $response = $this->createMock(Response::class);
        $builder->response($response);

        $this->assertEquals(0, $builder->written());
        $this->assertEquals(10, $builder->written(10));
        $this->assertEquals(15, $builder->written(5));

        $this->expectException(\Error::class);
        (new Builder())->written();
    }

    /**
     * Success test for method request.
     *
     * @return void
     */
    public function testRequest() {
        $builder = new Builder();
        $this->assertInstanceOf(Request::class, $builder->request($this->createMock(Request::class)));
    }

    /**
     * Success test for method response.
     *
     * @return void
     */
    public function testResponse() {
        $builder = new Builder();
        $this->assertInstanceOf(Response::class, $builder->response($this->createMock(Response::class)));
    }

}