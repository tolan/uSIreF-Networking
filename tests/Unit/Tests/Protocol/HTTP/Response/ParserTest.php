<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP\Response;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\{Request, Response, Response\Parser, Response\Code};
use uSIreF\Networking\Protocol\Exception;

/**
 * This file defines test class for HTTP Response parser.
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
    }

    /**
     * Success test for method request.
     *
     * @return void
     */
    public function testRequest() {
        $parser  = new Parser();
        $request = $this->createMock(Request::class);

        $this->expectException(Exception::class);
        $parser->request($request);
    }

    /**
     * Fail test for method response.
     *
     * @return void
     */
    public function testResponse() {
        $parser   = new Parser();
        $response = $this->createMock(Response::class);

        $this->assertNull($parser->response());
        $this->assertInstanceOf(Response::class, $parser->response($response));
        $this->assertNull($parser->response());
    }

    /**
     * Success test for method code.
     *
     * @return void
     */
    public function testCode() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $this->assertNotNull($parser->code());
        $this->assertEquals(Code::BAD_REQUEST_400, $parser->code(Code::BAD_REQUEST_400));
        $this->assertEquals(Code::BAD_REQUEST_400, $parser->code());
    }

    /**
     * Success test for method headers.
     *
     * @return void
     */
    public function testHeaders() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $this->assertNull($parser->headers());
        $this->assertEquals([], $parser->headers([]));
        $this->assertEquals([], $parser->headers());
        $this->assertEquals(['val'], $parser->headers(['val']));
        $this->assertEquals(['val', 'test'], $parser->headers(['val', 'test']));
    }

    /**
     * Success test for method httpVersion.
     *
     * @return void
     */
    public function testHttpVersion() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $this->assertNull($parser->httpVersion());
        $this->assertEquals('first', $parser->httpVersion('first'));
        $this->assertEquals('first', $parser->httpVersion());
        $this->assertEquals('second', $parser->httpVersion('second'));
        $this->assertEquals('second', $parser->httpVersion());
    }

    /**
     * Success test for method data.
     *
     * @return void
     */
    public function testData() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $this->assertNull($parser->data());
        $this->assertEquals([], $parser->data([]));
        $this->assertEquals([], $parser->data());
        $this->assertEquals(['val'], $parser->data(['val']));
        $this->assertEquals(['val', 'test'], $parser->data(['val', 'test']));
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
            $parser->addData($part);
        }

        $this->assertTrue($parser->isReadCompleted());
    }

    /**
     * Success test for method getHeader.
     *
     * @return void
     */
    public function testGetHeader() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $parts = [
            join([self::$_message['request_line'], self::$_message['header']], ''),
            self::$_message['body'],
        ];

        foreach ($parts as $part) {
            $parser->addData($part);
        }

        $this->assertEquals(25, $parser->getHeader('Content-Length'));
        $this->assertEquals(25, $parser->getHeader('content-length'));
        $this->assertEquals(null, $parser->getHeader('undefined'));
    }

    /**
     * Success test for method cleanup.
     *
     * @return void
     */
    public function testCleanup() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $this->assertInstanceOf(Parser::class, $parser->cleanup());
    }

    /**
     * Success test for _readHeaders with uncompleted headers.
     *
     * @return void
     */
    public function testForUncompleteHeaders() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);
        $parser->addData(self::$_message['request_line']);

        $this->assertFalse($parser->isReadCompleted());
    }

    /**
     * Success test for addData with chunked data.
     *
     * @return void
     */
    public function testForChunkedData() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $parts = [
            join(
                [
                    self::$_message['request_line'],
                    "Transfer-Encoding: chunked\r\n\r\n",
                ],
                ''
            ),
            "7\r\nMozilla\r\n",
            "9\r\nDeveloper\r\n",
            "7\r\nNetwork\r\n",
            "0\r\n\r\n",
        ];

        foreach ($parts as $part) {
            $this->assertFalse($parser->isReadCompleted());
            $parser->addData($part);
        }

        $this->assertTrue($parser->isReadCompleted());
    }

    /**
     * Success test for addData with uncompleted chunked data.
     *
     * @return void
     */
    public function testForUncompletedChunkedData() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $parts = [
            join(
                [
                    self::$_message['request_line'],
                    "Transfer-Encoding: chunked\r\n\r\n",
                ],
                ''
            ),
            "7\r\nMozilla\r\n",
            "9\r\nDeveloper\r\n",
            "7\r\nNetwork\r\n",
            '0',
        ];

        foreach ($parts as $part) {
            $this->assertFalse($parser->isReadCompleted());
            $parser->addData($part);
        }

        $this->assertFalse($parser->isReadCompleted());
    }

    /**
     * Success test for addData with missing data.
     *
     * @return void
     */
    public function testForChunkedDataWithMissingData() {
        $parser   = new Parser();
        $response = new Response();

        $parser->response($response);

        $parts = [
            join(
                [
                    self::$_message['request_line'],
                    "Transfer-Encoding: chunked\r\n\r\n",
                ],
                ''
            ),
            "7\r\nMozilla\r\n",
            "9\r\nDeveloper\r\n",
            "7\r\nNetwork\r\n",
            "2\r\n\r\n",
        ];

        foreach ($parts as $part) {
            $this->assertFalse($parser->isReadCompleted());
            $parser->addData($part);
        }

        $this->assertFalse($parser->isReadCompleted());
    }

}