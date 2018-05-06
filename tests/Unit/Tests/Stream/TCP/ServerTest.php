<?php

namespace uSIreF\Networking\Unit\Tests\Stream\TCP;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Stream\TCP\Server;
use uSIreF\Networking\Interfaces\Message\{IFactory, IMessage};

/**
 * This file defines test class for TCP stream server.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ServerTest extends TestCase {

    /**
     * Success test for protected method getAddress.
     *
     * @return void
     */
    public function testGetAddress() {
        $factory = $this->createMock(IFactory::class);
        $server  = new Server('localhost', 80, $factory);
        $method  = $this->getTestableMethod(Server::class, 'getAddress');

        $this->assertEquals('tcp://localhost:80', $method->invoke($server));
    }

    /**
     * Success test for protected method createMessage.
     *
     * @return void
     */
    public function testCreateMessage() {
        $message = $this->createMock(IMessage::class);
        $factory = $this->createMock(IFactory::class);
        $factory->expects($this->exactly(1))
            ->method('createIncomming')
            ->willReturn($message);

        $server  = new Server('localhost', 80, $factory);
        $method  = $this->getTestableMethod(Server::class, 'createMessage');

        $this->assertSame($message, $method->invoke($server, null));
    }

}