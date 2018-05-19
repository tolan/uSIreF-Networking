<?php

namespace uSIreF\Networking\Stream\UNIX;

use uSIreF\Networking\Interfaces\Message\{IFactory, IMessage};
use uSIreF\Networking\Stream\Abstracts\AServer;

/**
 * This file defines class for UNIX server stream.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Server extends AServer {

    /**
     * UNIX path descriptor
     *
     * @var string
     */
    private $_path;

    /**
     * Message factory.
     *
     * @var IFactory
     */
    private $_messageFactory;

    /**
     * Construct method for set descriptor.
     *
     * @param string   $path    UNIX descriptor (/tmp/usiref_descriptor)
     * @param IFactory $factory Message factory instance
     *
     * @return void
     */
    public function __construct(string $path, IFactory $factory) {
        if (file_exists($path)) {
            unlink($path);
        }

        $this->_path           = $path;
        $this->_messageFactory = $factory;
    }

    /**
     * Returns server address.
     *
     * @return string
     */
    protected function getAddress(): string {
        return 'unix://'.$this->_path;
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