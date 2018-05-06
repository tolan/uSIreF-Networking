<?php

namespace uSIreF\Networking\Unit\Tests\Message\Abstracts;

use uSIreF\Networking\Message\Abstracts;
use uSIreF\Networking\Interfaces\Message\IMessage;

/**
 * This file defines test class for Abstract Message. This is only for tests!
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class AMessage extends Abstracts\AMessage {

    /**
     * Next status.
     *
     * @var string
     */
    private $_nextStatus;

    /**
     * It updates message like a status, etc.
     *
     * @return IMessage
     */
    public function update(): IMessage {
        $this->setStatus($this->_nextStatus);

        return $this;
    }

    /**
     * Sets next status.
     *
     * @param string $status Next status
     *
     * @return IMessage
     */
    public function nextStatus(string $status): IMessage {
        $this->_nextStatus = $status;

        return $this;
    }

    /**
     * Proxy function for call read.
     *
     * @return IMessage
     */
    public function callRead(): IMessage {
        return $this->read();
    }

    /**
     * Proxy function for call write.
     *
     * @return IMessage
     */
    public function callWrite(): IMessage {
        return $this->write();
    }

}