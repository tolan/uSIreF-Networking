<?php

namespace uSIreF\Networking;

use uSIreF\Networking\Interfaces\{Message\IMessage, Stream\IServer};
use Closure;

/**
 * This file defines class for networking server.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Server {

    /**
     * Stream adapter.
     *
     * @var IServer
     */
    private $_stream;

    /**
     * List of active messages.
     *
     * @var [IMessage]
     */
    private $_messages = [];

    /**
     * List of catched erros.
     *
     * @var array
     */
    private $_errors = [];

    /**
     * Construct method for inject server stream adapter.
     *
     * @param IServer $stream Server stream instance
     *
     * @return void
     */
    public function __construct(IServer $stream) {
        $this->_stream = $stream;
    }

    /**
     * Destruct method for disconnecting all messages and stop stream.
     *
     * @return void
     */
    public function __destruct() {
        foreach ($this->_messages as $key => $message) {
            unset($this->_messages[$key]);
            $message->close();
        }

        $this->_stream->stop();
    }

    /**
     * Starts and runs server listening forever.
     *
     * @param Closure $loopCallback Callback wich is called in each iteration (optional)
     *
     * @return Server
     */
    public function run(Closure $loopCallback = null): Server {
        $this->_stream->stop()
            ->start();

        $condition = ($loopCallback ?? function() {
            return true;
        });

        while ($condition($this)) {
            $timeout = empty($this->_messages) ? 1000 : 1;
            $message = $this->_stream->select($timeout);
            if ($message) {
                $this->_messages[] = $message;
            }

            foreach ($this->_messages as $key => $message) { /* @var $message IMessage */
                try {
                    $message->update();
                    if ($message->isCompleted() || $message->isTimeoutReached()) {
                        unset($this->_messages[$key]);
                        $message->close();
                    }
                } catch (Exception $e) {
                    $this->_errors[] = $e;
                    $message->close();
                    unset($this->_messages[$key]);
                }
            }
        }

        return $this;
    }

    /**
     * It returns and cleans catched errors.
     *
     * @return []
     */
    public function flushErrors(): array {
        $errors        = $this->_errors;
        $this->_errors = [];

        return $errors;
    }

}