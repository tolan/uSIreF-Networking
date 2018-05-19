<?php

namespace uSIreF\Networking\Message\Abstracts;

use uSIreF\Networking\Interfaces\Message\{IMessage, IPlugin};
use uSIref\Networking\Interfaces\Protocol\{IRequest, IResponse, IParser, IBuilder};
use uSIref\Networking\Interfaces\Stream\IConnection;
use SplObserver;

/**
 * This file defines abstract class for Message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
abstract class AMessage implements IMessage {

    const MESSAGE_TIMEOUT = (30 * 1000);

    /**
     * Set of assigned plugins.
     *
     * @var [IPlugin]
     */
    private $_plugins = [];

    /**
     * Assigned connection.
     *
     * @var IConnection
     */
    private $_connection;

    /**
     * Message request.
     *
     * @var IRequest
     */
    private $_request;

    /**
     * Message response.
     *
     * @var IResponse
     */
    private $_response;

    /**
     * Message parser.
     *
     * @var IParser
     */
    private $_parser;

    /**
     * Message builder.
     *
     * @var IBuilder
     */
    private $_builder;

    /**
     * Message status.
     *
     * @var string
     */
    private $_status = self::STATUS_OPEN;

    /**
     * Construct method for set connection, request and response.
     *
     * @param IConnection $connection Connection instance
     * @param IRequest    $request    Request instance
     * @param IResponse   $response   Response instance
     * @param IParser     $parser     Parser instance
     * @param IBuilder    $builder    Builder instance
     *
     * @return void
     */
    public function __construct(IConnection $connection, IRequest $request, IResponse $response, IParser $parser, IBuilder $builder) {
        $this->_start      = microtime(true);
        $this->_connection = $connection;
        $this->_request    = $request;
        $this->_response   = $response;
        $this->_parser     = $parser;
        $this->_builder    = $builder;
    }

    /**
     * Adds plugin to message which will be notified.
     *
     * @param SplObserver $observer Message plugin instance
     *
     * @return void
     */
    public function attach(SplObserver $observer): void {
        $this->_plugins[] = $observer;
    }

    /**
     * Removes plugin from the message.
     *
     * @param SplObserver $observer Message plugin instance
     *
     * @return void
     */
    public function detach(SplObserver $observer): void {
        $plugins = $this->_plugins;

        foreach ($plugins as $key => $plugin) {
            if ($observer === $plugin) {
                unset($plugins[$key]);
            }
        }

        $this->_plugins = array_values($plugins);
    }

    /**
     * Returns message status.
     *
     * @return string
     */
    public function getStatus(): string {
        return $this->_status;
    }

    /**
     * Retruns assigned connection.
     *
     * @return IConnection
     */
    public function getConnection(): IConnection {
        return $this->_connection;
    }

    /**
     * Returns assigned request.
     *
     * @return IRequest
     */
    public function getRequest(): IRequest {
        return $this->_request;
    }

    /**
     * Returns assigned response.
     *
     * @return IResponse
     */
    public function getResponse(): IResponse {
        return $this->_response;
    }

    /**
     * Returns that the timeout is reached.
     *
     * @return bool
     */
    public function isTimeoutReached(): bool {
        return (microtime(true) - $this->_start) > self::MESSAGE_TIMEOUT;
    }

    /**
     * Returns that the message is completed.
     *
     * @return bool
     */
    public function isCompleted(): bool {
        return $this->_status === self::STATUS_COMPLETED;
    }

    /**
     * It closes message.
     *
     * @return IMessage
     */
    public function close(): IMessage {
        $this->_connection->close();
        $this->setStatus(self::STATUS_CLOSED);

        return $this;
    }

    /**
     * Returns message parser.
     *
     * @return IParser
     */
    protected function getParser(): IParser {
        return $this->_parser;
    }

    /**
     * Returns message builder.
     *
     * @return IBuilder
     */
    protected function getBuilder(): IBuilder {
        return $this->_builder;
    }

    /**
     * Sets new message status and notify plugins.
     *
     * @param string $status Message status (one of enum STATUS_*)
     *
     * @return IMessage
     */
    protected function setStatus(string $status): IMessage {
        if ($this->_status !== $status) {
            $this->_status = $status;
            $this->_notify();
        }

        return $this;
    }

    /**
     * Reads data from connection and data puts into parser.
     *
     * @return IMessage
     */
    protected function read(): IMessage {
        $this->setStatus(self::STATUS_READING);
        $data = $this->_connection->read();
        if (!empty($data) && $this->getParser()->addData($data)->isReadCompleted() === true) {
            $this->setStatus(self::STATUS_READ_COMPLETE);
        }

        return $this;
    }

    /**
     * Writes data to connection and answer's length sets to builder.
     *
     * @return IMessage
     */
    protected function write(): IMessage {
        $this->setStatus(self::STATUS_WRITING);
        $message = $this->getBuilder()->render();
        $written = $this->_connection->write($message);
        $this->getBuilder()->written($written);

        if ($this->getBuilder()->isWriteCompleted() === true) {
            $this->setStatus(self::STATUS_WRITE_COMPLETE);
        }

        return $this;
    }

    /**
     * It notifies all assigned plugins.
     *
     * @return IMessage
     */
    private function _notify(): IMessage {
        foreach ($this->_plugins as $plugin) { /* @var $plugin IPlugin */
            $plugin->update($this);
        }

        return $this;
    }

}