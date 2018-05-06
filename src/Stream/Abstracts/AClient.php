<?php

namespace uSIreF\Networking\Stream\Abstracts;

use uSIreF\Networking\Interfaces\Stream\IClient;
use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Interfaces\Message\IMessage;
use uSIreF\Networking\Stream\Exception;

/**
 * This file defines abstract class for stream client.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
abstract class AClient implements IClient {

    /**
     * Connects to endpoint by request.
     *
     * @param IRequest $request Protocol request
     * @param float    $timeout Timeout for connection
     *
     * @throws Exception
     *
     * @return IMessage
     */
    public function connect(IRequest $request, float $timeout = 0): IMessage {
        $errno   = null;
        $errstr  = '';
        $address = $this->getAddress($request);
        $socket  = stream_socket_client($address, $errno, $errstr, $timeout);
        if (!$socket) {
            throw new Exception('Could not connect to a server "'.$address.'": ('.$errno.') - '.$errstr);
        }

        stream_set_timeout($socket, 0, 250);

        return $this->createMessage($socket, $request);
    }

    /**
     * Returns server address.
     *
     * @param IRequest $request Protocol request
     *
     * @return string
     */
    abstract protected function getAddress(IRequest $request): string;

    /**
     * Creates outgoing message by request.
     *
     * @param resource $socket  Stream socket
     * @param IRequest $request Protocol request
     *
     * @return IMessage
     */
    abstract protected function createMessage($socket, IRequest $request): IMessage;

}