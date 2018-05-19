<?php

namespace uSIreF\Networking\Stream\TCP;

use uSIreF\Networking\Interfaces\Message\{IFactory, IMessage};
use uSIreF\Networking\Stream\Abstracts\AServer;

/**
 * This file defines class for TCP server stream.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Server extends AServer {

    /**
     * Hostname
     *
     * @var string
     */
    private $_host;

    /**
     * Port
     *
     * @var integer
     */
    private $_port;

    /**
     * Message factory.
     *
     * @var IFactory
     */
    private $_messageFactory;

    /**
     * Construct method for set host, port and message factory.
     *
     * @param string   $host    Hostname where server will listen to.
     * @param int      $port    Port where server will listen to.
     * @param IFactory $factory Message factory instance
     *
     * @return void
     */
    public function __construct(string $host, int $port, IFactory $factory) {
        $this->_host           = $host;
        $this->_port           = $port;
        $this->_messageFactory = $factory;
    }

    /**
     * Returns server address.
     *
     * @return string
     */
    protected function getAddress(): string {
        return 'tcp://'.$this->_host.':'.$this->_port;
    }

    /**
     * Creates incomming message.
     *
     * @param resource $socket Stream socket
     *
     * @return IMessage
     */
    protected function createMessage($socket): IMessage {
        return $this->_messageFactory->createIncomming(new Connection($socket));
    }

}