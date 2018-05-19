<?php

namespace uSIreF\Networking\Unit\Tests\Stream\UNIX;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Stream\UNIX\Client;
use uSIreF\Networking\Interfaces\Message\{IFactory, IMessage};
use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Protocol\uSIreF\Request;

/**
 * This file defines test class for UNIX stream client.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ClientTest extends TestCase {

    /**
     * Success test for protected method getAddress.
     *
     * @return void
     */
    public function testGetAddress() {
        $factory = $this->createMock(IFactory::class);
        $client  = new Client($factory);
        $method  = $this->getTestableMethod(Client::class, 'getAddress');

        $request = $this->createMock(IRequest::class);
        $request->expects($this->exactly(1))
            ->method('requestUri')
            ->willReturn('/test');

        $this->assertEquals('unix:///test', $method->invoke($client, $request));
    }

    /**
     * Success test for protected method createMessage.
     *
     * @return void
     */
    public function testCreateMessage() {
        $destinationRequest = $this->createMock(Request::class);
        $destinationRequest->expects($this->exactly(1))
            ->method('from')
            ->with([
                'requestUri' => 'test',
                'data' => 'data',
            ]);

        $message = $this->createMock(IMessage::class);
        $message->expects($this->exactly(1))
            ->method('getRequest')
            ->willReturn($destinationRequest);

        $factory = $this->createMock(IFactory::class);
        $factory->expects($this->exactly(1))
            ->method('createOutgoing')
            ->willReturn($message);

        $client  = new Client($factory);
        $method  = $this->getTestableMethod(Client::class, 'createMessage');
        $sourceRequest = new Request();
        $sourceRequest->requestUri('test');
        $sourceRequest->data('data');

        $method->invoke($client, null, $sourceRequest);
    }

}