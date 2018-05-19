<?php

namespace uSIreF\Networking\Unit\Tests;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Client;
use uSIreF\Networking\Interfaces\Stream\IClient;
use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Interfaces\Message\IMessage;

/**
 * This file defines test class for networking Client.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ClientTest extends TestCase {

    /**
     * Success test for method send.
     *
     * @return void
     */
    public function testSend() {
        $stream  = $this->createMock(IClient::class);
        $message = $this->createMock(IMessage::class);
        $request = $this->createMock(IRequest::class);

        $stream->expects($this->exactly(1))
            ->method('connect')
            ->with($request, 10)
            ->willReturn($message);

        $message->expects($this->exactly(1))
            ->method('notify');

        $client = new Client($stream);

        $client->send($request, 10);
    }

}