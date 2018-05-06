<?php

namespace uSIreF\Networking\Unit\Tests\Protocol\uSIreF;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Protocol\uSIreF\{Factory, Builder, Parser, Request, Response};

/**
 * This file defines test class for uSIreF Factory.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class FactoryTest extends TestCase {

    /**
     * Sucess test for method createRequest.
     *
     * @return void
     */
    public function testCreateRequest() {
        $factory = new Factory();
        $this->assertInstanceOf(Request::class, $factory->createRequest());
    }

    /**
     * Sucess test for method createResponse.
     *
     * @return void
     */
    public function testCreateResponse() {
        $factory = new Factory();
        $this->assertInstanceOf(Response::class, $factory->createResponse());
    }

    /**
     * Sucess test for method createParser.
     *
     * @return void
     */
    public function testCreateParser() {
        $factory = new Factory();
        $this->assertInstanceOf(Parser::class, $factory->createParser());
    }

    /**
     * Sucess test for method createBuilder.
     *
     * @return void
     */
    public function testCreateBuilder() {
        $factory = new Factory();
        $this->assertInstanceOf(Builder::class, $factory->createBuilder());
    }

}