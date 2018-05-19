<?php

namespace uSIreF\Networking\Unit\Tests\Stream\TCP;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Stream\TCP\Client;
use uSIreF\Networking\Interfaces\Message\{IFactory, IMessage};
use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Protocol\HTTP\Request;
use uSIreF\Networking\Stream\Exception;

/**
 * This file defines test class for TCP stream client.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ClientTest extends TestCase {

    /**
     * Success test for protected method getAddress.
     *
     * @return void
     */
    public function testGetAddressSuccess() {
        $data = [
            'tcp://localhost:9090',
            'tcp://localhost:80' => 'http://localhost',
            'tcp://localhost:8080' => 'localhost:8080',
            'tcp://username:password@hostname:9090/path?arg=value#anchor' => 'http://username:password@hostname:9090/path?arg=value#anchor',
        ];

        $factory = $this->createMock(IFactory::class);
        $client  = new Client($factory);
        $method  = $this->getTestableMethod(Client::class, 'getAddress');
        $request = $this->createMock(IRequest::class);
        $request->expects($this->any())
            ->method('requestUri')
            ->willReturn(...array_values($data));

        foreach ($data as $key => $item) {
            $key = is_string($key) ? $key : $item;
            $this->assertEquals($key, $method->invoke($client, $request));
        }
    }

    /**
     * Fail test for protected method getAddress. It fails because missing port.
     *
     * @return void
     */
    public function testGetAddressUnresolvablePort() {
        $factory = $this->createMock(IFactory::class);
        $client  = new Client($factory);
        $method  = $this->getTestableMethod(Client::class, 'getAddress');
        $request = $this->createMock(IRequest::class);
        $request->expects($this->any())
            ->method('requestUri')
            ->willReturn('localhost');

        $this->expectException(Exception::class);
        $method->invoke($client, $request);
    }

    /**
     * Fail test for protected method getAddress. It fails because host is invalid.
     *
     * @return void
     */
    public function testGetAddressUnresolvableHost() {
        $factory = $this->createMock(IFactory::class);
        $client  = new Client($factory);
        $method  = $this->getTestableMethod(Client::class, 'getAddress');
        $request = $this->createMock(IRequest::class);
        $request->expects($this->any())
            ->method('requestUri')
            ->willReturn('http://:80');

        $this->expectException(Exception::class);
        $method->invoke($client, $request);
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