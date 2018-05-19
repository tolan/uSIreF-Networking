<?php

namespace uSIreF\Networking\Interfaces\Message;

use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse};
use SplSubject;
use SplObserver;

/**
 * This file defines interface for message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IMessage extends SplSubject {

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
     * @param SplObserver $observer Message plugin instance
     *
     * @return void
     */
    public function attach(SplObserver $observer): void;

    /**
     * Removes plugin from the message.
     *
     * @param SplObserver $observer Message plugin instance
     *
     * @return void
     */
    public function detach(SplObserver $observer): void;

    /**
     * It updates message like a status, etc.
     *
     * @return IMessage
     */
    public function notify(): void;

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