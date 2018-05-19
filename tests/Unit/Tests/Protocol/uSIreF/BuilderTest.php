<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\uSIreF;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\uSIreF\{Builder, Request, Response};

/**
 * This file defines test class for uSIreF Builder.
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
     * Success test for method isReadCompleted.
     *
     * @return void
     */
    public function testIsWriteCompleted() {
        $builder  = new Builder();
        $response = $this->createMock(Response::class);
        $response->expects($this->exactly(5))
            ->method('data')
            ->willReturn(null, 'message', 'message', 'message', 'message');

        $builder->response($response);

        $this->assertFalse($builder->isWriteCompleted());
        $builder->written(5);
        $this->assertFalse($builder->isWriteCompleted());
        $builder->written(4);
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
        $response->expects($this->any())
            ->method('data')
            ->willReturn('this is a message|and separator');

        $builder->response($response);

        $this->assertEquals('31|this is a message|and separator', $builder->render());
        $builder->written(10);
        $this->assertEquals(' a message|and separator', $builder->render());
        $builder->written(12);
        $this->assertEquals('nd separator', $builder->render());

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