<?php

namespace uSIreF\Networking\Interfaces\Message;

use uSIreF\Networking\Interfaces\Stream\IConnection;

/**
 * This file defines interface for message factory.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IFactory {

    /**
     * Adds plugin which will be assigned to each message.
     *
     * @param IPlugin $plugin Message plugin instance
     *
     * @return IFactory
     */
    public function addPlugin(IPlugin $plugin): IFactory;

    /**
     * Creates incomming message.
     *
     * @param IConnection $connection Stream connection instance
     *
     * @return IMessage
     */
    public function createIncomming(IConnection $connection): IMessage;

    /**
     * Creates outgoing message.
     *
     * @param IConnection $connection Stream connection instance
     *
     * @return IMessage
     */
    public function createOutgoing(IConnection $connection): IMessage;

}