<?php

namespace uSIreF\Networking;

use uSIreF\Networking\Interfaces\Stream\IClient;
use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Interfaces\Message\IMessage;

/**
 * This file defines class for networking client.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Client {

    /**
     * Client stream instance.
     *
     * @var IClient
     */
    private $_stream;

    /**
     * Construct method for inject client stream instance.
     *
     * @param IClient $stream Client stream instance
     *
     * @return void
     */
    public function __construct(IClient $stream) {
        $this->_stream = $stream;
    }

    /**
     * Sends request and returns message.
     *
     * @param IRequest $request Protocol request instance
     * @param float    $timeout Timeout for connection
     *
     * @return IMessage
     */
    public function send(IRequest $request, float $timeout = 0): IMessage {
        $message = $this->_stream->connect($request, $timeout);
        $message->notify();

        return $message;
    }

}