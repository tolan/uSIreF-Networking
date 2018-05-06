<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\{Parser, Request, Response};

/**
 * This file defines test class for HTTP Parser.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ParserTest extends TestCase {

    /**
     * Test message definition separated to parts.
     *
     * @var array
     */
    private static $_message = [
        'request_line' => "HTTP/1.1 200 OK\r\n",
        'header'       => "Content-Length: 25\r\n\r\n",
        'body'         => 'message=this+is+a+message',
    ];

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
        $parser->addData(join(self::$_message, ''));
        $this->assertTrue($parser->isReadCompleted());

        $this->expectException(\Error::class);
        (new Parser())->isReadCompleted();
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

        $parts = [
            join([self::$_message['request_line'], self::$_message['header']], ''),
            self::$_message['body'],
        ];

        foreach ($parts as $part) {
            $this->assertFalse($parser->isReadCompleted());
            $this->assertInstanceOf(Parser::class, $parser->addData($part));
        }

        $this->assertTrue($parser->isReadCompleted());

        $this->expectException(\Error::class);
        (new Parser())->addData('test');
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