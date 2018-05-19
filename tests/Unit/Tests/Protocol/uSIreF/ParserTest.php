<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\uSIreF;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\uSIreF\{Parser, Request, Response};

/**
 * This file defines test class for uSIreF Parser.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ParserTest extends TestCase {

    /**
     * Success test for method isReadCompleted.
     *
     * @return void
     */
    public function testIsReadCompleted() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $this->assertFalse($parser->isReadCompleted());
        $parser->addData('7|message');
        $this->assertTrue($parser->isReadCompleted());
    }

    /**
     * Success test for addData.
     *
     * @return void
     */
    public function testAddData() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $parts = ['1', '7|', 'this is', ' a message'];

        foreach ($parts as $part) {
            $this->assertFalse($parser->isReadCompleted());
            $this->assertInstanceOf(Parser::class, $parser->addData($part));
        }

        $this->assertTrue($parser->isReadCompleted());

        $this->expectException(\Error::class);
        (new Parser())->addData('4|test');
    }

    /**
     * Success test for method request.
     *
     * @return void
     */
    public function testRequest() {
        $parser = new Parser();
        $this->assertInstanceOf(Request::class, $parser->request($this->createMock(Request::class)));
    }

    /**
     * Success test for method response.
     *
     * @return void
     */
    public function testResponse() {
        $parser = new Parser();
        $this->assertInstanceOf(Response::class, $parser->response($this->createMock(Response::class)));
    }

}