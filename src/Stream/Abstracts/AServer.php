<?php

namespace uSIreF\Networking\Stream\Abstracts;

use uSIreF\Networking\Interfaces\Stream\IServer;
use uSIreF\Networking\Interfaces\Message\IMessage;
use uSIreF\Networking\Stream\Utils\Timeout;
use uSIreF\Networking\Stream\Exception;

/**
 * This file defines abstract class for stream server.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
abstract class AServer implements IServer {

    /**
     * Socket stream connection
     *
     * @var resource
     */
    private $_server;

    /**
     * Creates and starts server.
     *
     * @throws Exception
     *
     * @return IServer
     */
    public function start(): IServer {
        set_time_limit(0);

        if ($this->_server) {
            throw new Exception('Could not start server again.');
        }

        $errno   = null;
        $errstr  = '';
        $address = $this->getAddress();

        $this->_server = stream_socket_server($address, $errno, $errstr);
        if (!$this->_server) {
            throw new Exception('Could not start a server on "'.$address.'" - ('.$errno.') '.$errstr);
        }

        stream_set_blocking($this->_server, 0);

        return $this;
    }

    /**
     * Select Message from server stream.
     *
     * @param float $timeout Timeout for select message in ms
     *
     * @throws Exception
     *
     * @return IMessage|null
     */
    public function select(float $timeout = 0): ?IMessage {
        if (!$this->_server) {
            throw new Exception('Could not select because server is not started.');
        }

        $toRead  = [$this->_server];
        $toWrite = [];
        $except  = null;

        list($sec, $usec) = Timeout::toArray($timeout);

        stream_select($toRead, $toWrite, $except, $sec, $usec);

        $message = null;
        // new client connection
        if (in_array($this->_server, $toRead)) {
            $socket  = stream_socket_accept($this->_server);
            $message = $this->createMessage($socket);
        }

        return $message;
    }

    /**
     * Stops server.
     *
     * @return IServer
     */
    public function stop(): IServer {
        if ($this->_server) {
            fclose($this->_server);
            $this->_server = null;
        }

        return $this;
    }

    /**
     * Destruct method for cleanup.
     *
     * @return void
     */
    public function __destruct() {
        $this->stop();
    }

    /**
     * Returns server address.
     *
     * @return string
     */
    abstract protected function getAddress(): string;

    /**
     * Creates incomming message.
     *
     * @param resource $socket Stream socket
     *
     * @return IMessage
     */
    abstract protected function createMessage($socket): IMessage;
}