<?php

namespace uSIreF\Networking\Message\Plugins;

use uSIreF\Networking\Interfaces\Message\{IMessage, IPlugin};
use SplSubject;

/**
 * This file defines class for message plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Status implements IPlugin {

    /**
     * Previous state of message.
     *
     * @var string
     */
    private $_previous = IMessage::STATUS_OPEN;

    /**
     * Actual state of message.
     *
     * @var string
     */
    private $_actual = IMessage::STATUS_OPEN;

    /**
     * Observed message.
     *
     * @var IMessage
     */
    private $_message;

    /**
     * Reinitialize status.
     *
     * @return void
     */
    public function __clone() {
        $this->_previous = IMessage::STATUS_OPEN;
        $this->_actual   = IMessage::STATUS_OPEN;
        $this->_message  = null;
    }

    /**
     * Updates plugin when observed message is changed.
     *
     * @param SplSubject $subject Message instance
     *
     * @return void
     */
    public function update(SplSubject $subject): void {
        $this->_message  = $subject;
        $this->_previous = $this->_actual;

        $status = $subject->getStatus();
        if ($this->_actual !== $status) {
            $this->_actual = $status;
        }
    }

    /**
     * Returns that state of message has changed.
     *
     * @return boolean
     */
    public function isChanged(): bool {
        return $this->_actual !== $this->_previous;
    }

    /**
     * Returns observed message.
     *
     * @return IMessage|null
     */
    public function getMessage(): ?IMessage {
        return $this->_message;
    }

}