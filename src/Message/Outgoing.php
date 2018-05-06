<?php

namespace uSIreF\Networking\Message;

use uSIreF\Networking\Interfaces\Message\IMessage;

/**
 * This file defines class for outgoing message (to server from client).
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Outgoing extends Abstracts\AMessage {

    /**
     * It updates message like a status, etc.
     *
     * @return IMessage
     */
    public function update(): IMessage {
        $parser  = $this->getParser();
        $builder = $this->getBuilder();
        if ($builder->isReadCompleted() === true && $builder->isWriteCompleted() === false) {
            $this->write();
        } else if ($parser->isReadCompleted() === false) {
            $this->read();
        }

        if ($builder->isWriteCompleted() === true && $parser->isReadCompleted() === true) {
            $this->setStatus(self::STATUS_COMPLETED);
        }

        return $this;
    }

}