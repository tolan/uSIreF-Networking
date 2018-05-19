<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\HTTP\Response;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\HTTP\Response\Code;
use uSIreF\Networking\Protocol\Exception;

/**
 * This file defines test class for HTTP Response code.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class CodeTest extends TestCase {

    /**
     * Test for method getMessage.
     *
     * @return void
     */
    public function testGetMessage() {
        $this->assertEquals('Continue', Code::getMessage(Code::CONTINUE_100));
        $this->assertEquals('OK', Code::getMessage(Code::OK_200));

        $this->expectException(Exception::class);
        Code::getMessage(0);
    }

    /**
     * Test for method renderStatus.
     *
     * @return void
     */
    public function testRenderStatus() {
        $this->assertEquals('HTTP/1.1 '.Code::CONTINUE_100." Continue\r\n", Code::renderStatus(Code::CONTINUE_100));
        $this->assertEquals('HTTP/1.1 '.Code::BAD_REQUEST_400." Bad Request\r\n", Code::renderStatus(Code::BAD_REQUEST_400));
        $this->assertEquals('HTTP/1.1 '.Code::BAD_REQUEST_400." Custom message\r\n", Code::renderStatus(Code::BAD_REQUEST_400, 'Custom message'));

        $this->expectException(Exception::class);
        Code::renderStatus(0);
    }

}