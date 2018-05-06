<?php

namespace uSIreF\Networking\Stream\UNIX;

use uSIreF\Networking\Stream\Abstracts\AClient;
use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Interfaces\Message\{IFactory, IMessage};

/**
 * This file defines class for UNIX stream client
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Client extends AClient {

    /**
     * Message factory.
     *
     * @var IFactory
     */
    private $_messageFactory;

    /**
     * Construct method for inject message factory.
     *
     * @param IFactory $factory Message factory instance
     *
     * @return void
     */
    public function __construct(IFactory $factory) {
        $this->_messageFactory = $factory;
    }

    /**
     * Returns server address.
     *
     * @param IRequest $request Protocol request
     *
     * @return string
     */
    protected function getAddress(IRequest $request): string {
        return 'unix://'.$request->requestUri();
    }

    /**
     * Creates outgoing message by request.
     *
     * @param resource $socket  Stream socket
     * @param IRequest $request Protocol request
     *
     * @return IMessage
     */
    protected function createMessage($socket, IRequest $request): IMessage {
        $message = $this->_messageFactory->createOutgoing(new Connection($socket));
        $message->getRequest()->from($request->to());

        return $message;
    }

}