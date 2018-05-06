<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP\Request;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\{Request, Response, Request\Parser, Request\Method};
use uSIreF\Networking\Protocol\Exception;

/**
 * This file defines test class for HTTP Request parser.
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
        'request_line' => "POST /uri?key=value+1 HTTP/1.1\r\n",
        'header_1'     => "Host: uri\r\n",
        'header_2'     => "content-length: 58\r\n\r\n",
        'body_1'       => 'message=___first+part+of+body___',
        'body_2'       => '+___second+part+of+body___',
    ];

    /**
     * Success test for method isReadCompleted.
     *
     * @return void
     */
    public function testIsReadCompleted() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

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

        $this->assertNull($parser->request());
        $this->assertInstanceOf(Request::class, $parser->request($request));
        $this->assertNull($parser->request());
    }

    /**
     * Fail test for method response.
     *
     * @return void
     */
    public function testResponse() {
        $parser   = new Parser();
        $response = $this->createMock(Response::class);

        $this->expectException(Exception::class);
        $parser->response($response);
    }

    /**
     * Success test for method method.
     *
     * @return void
     */
    public function testMethod() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $this->assertNull($parser->method());
        $this->assertEquals(Method::GET, $parser->method(Method::GET));
        $this->assertEquals(Method::GET, $parser->method());
        $this->assertEquals(Method::POST, $parser->method(Method::POST));
        $this->assertEquals(Method::POST, $parser->method());
    }

    /**
     * Success test for method requestUri.
     *
     * @return void
     */
    public function testRequestUri() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $this->assertNull($parser->requestUri());
        $this->assertEquals('first', $parser->requestUri('first'));
        $this->assertEquals('first', $parser->requestUri());
        $this->assertEquals('second', $parser->requestUri('second'));
        $this->assertEquals('second', $parser->requestUri());
    }

    /**
     * Success test for method uri.
     *
     * @return void
     */
    public function testUri() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $this->assertNull($parser->uri());
        $this->assertEquals('first', $parser->uri('first'));
        $this->assertEquals('first', $parser->uri());
        $this->assertEquals('second', $parser->uri('second'));
        $this->assertEquals('second', $parser->uri());
    }

    /**
     * Success test for method httpVersion.
     *
     * @return void
     */
    public function testHttpVersion() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $this->assertNull($parser->httpVersion());
        $this->assertEquals('first', $parser->httpVersion('first'));
        $this->assertEquals('first', $parser->httpVersion());
        $this->assertEquals('second', $parser->httpVersion('second'));
        $this->assertEquals('second', $parser->httpVersion());
    }

    /**
     * Success test for method query.
     *
     * @return void
     */
    public function testQuery() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $this->assertNull($parser->query());
        $this->assertEquals([], $parser->query([]));
        $this->assertEquals([], $parser->query());
        $this->assertEquals(['val'], $parser->query(['val']));
        $this->assertEquals(['val', 'test'], $parser->query(['val', 'test']));
    }

    /**
     * Success test for method headers.
     *
     * @return void
     */
    public function testHeaders() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $this->assertNull($parser->headers());
        $this->assertEquals([], $parser->headers([]));
        $this->assertEquals([], $parser->headers());
        $this->assertEquals(['val'], $parser->headers(['val']));
        $this->assertEquals(['val', 'test'], $parser->headers(['val', 'test']));
    }

    /**
     * Success test for method data.
     *
     * @return void
     */
    public function testData() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

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
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $parts = [
            join([self::$_message['request_line'], self::$_message['header_1'], self::$_message['header_2']], ''),
            self::$_message['body_1'],
            self::$_message['body_2'],
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
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $parts = [
            join([self::$_message['request_line'], self::$_message['header_1'], self::$_message['header_2']], ''),
            self::$_message['body_1'],
            self::$_message['body_2'],
        ];

        foreach ($parts as $part) {
            $parser->addData($part);
        }

        $this->assertEquals('uri', $parser->getHeader('Host'));
        $this->assertEquals('uri', $parser->getHeader('host'));
        $this->assertEquals(null, $parser->getHeader('undefined'));
    }

    /**
     * Success test for method cleanup.
     *
     * @return void
     */
    public function testCleanup() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

        $this->assertInstanceOf(Parser::class, $parser->cleanup());
    }

    /**
     * Success test for _readHeaders with uncompleted headers.
     *
     * @return void
     */
    public function testForUncompleteHeaders() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);
        $parser->addData(self::$_message['request_line']);

        $this->assertFalse($parser->isReadCompleted());
    }

    /**
     * Success test for addData with chunked data.
     *
     * @return void
     */
    public function testForChunkedData() {
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

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
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

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
        $parser  = new Parser();
        $request = new Request();

        $parser->request($request);

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