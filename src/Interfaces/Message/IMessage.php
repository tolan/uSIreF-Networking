<?php

namespace uSIreF\Networking\Interfaces\Message;

use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse};

/**
 * This file defines interface for message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IMessage {

    const STATUS_OPEN           = 'open';
    const STATUS_READING        = 'reading';
    const STATUS_READ_COMPLETE  = 'read_complete';
    const STATUS_WRITING        = 'writing';
    const STATUS_WRITE_COMPLETE = 'write_complete';
    const STATUS_COMPLETED      = 'completed';
    const STATUS_CLOSED         = 'closed';

    /**
     * Adds plugin to message which will be notified.
     *
     * @param IPlugin $plugin Message plugin instance
     *
     * @return IMessage
     */
    public function addPlugin(IPlugin $plugin): IMessage;

    /**
     * Returns that the timeout is reached.
     *
     * @return bool
     */
    public function isTimeoutReached(): bool;

    /**
     * Returns that the message is completed.
     *
     * @return bool
     */
    public function isCompleted(): bool;

    /**
     * Returns message status.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * It closes message.
     *
     * @return IMessage
     */
    public function close(): IMessage;

    /**
     * It updates message like a status, etc.
     *
     * @return IMessage
     */
    public function update(): IMessage;

    /**
     * Returns assigned request.
     *
     * @return IRequest
     */
    public function getRequest(): IRequest;

    /**
     * Returns assigned response.
     *
     * @return IResponse
     */
    public function getResponse(): IResponse;

}