<?php

namespace uSIreF\Networking\Interfaces\Stream;

use uSIreF\Networking\Interfaces\Message\IMessage;

/**
 * This file defines interface for server side of stream.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IServer {

    /**
     * Starts server listening.
     *
     * @return IServer
     */
    public function start(): IServer;

    /**
     * Selects incomming message.
     *
     * @param float $timeout Timeout for selecting
     *
     * @return IMessage|null
     */
    public function select(float $timeout = 0): ?IMessage;

    /**
     * Stops server listening.
     *
     * @return IServer
     */
    public function stop(): IServer;

}