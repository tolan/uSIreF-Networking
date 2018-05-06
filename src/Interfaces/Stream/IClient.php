<?php

namespace uSIreF\Networking\Interfaces\Stream;

use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Interfaces\Message\IMessage;

/**
 * This file defines interface for client side of stream.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IClient {

    /**
     * Connects to endpoint by request.
     *
     * @param IRequest $request Protocol request
     * @param float    $timeout Timeout for connection
     *
     * @return IMessage
     */
    public function connect(IRequest $request, float $timeout = 0): IMessage;

}