<?php

namespace uSIreF\Networking\Message;

use uSIreF\Networking\Interfaces\Message\IMessage;

/**
 * This file defines class for incomming message (from client on server).
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Incomming extends Abstracts\AMessage {

    /**
     * It updates message like a status, etc.
     *
     * @return IMessage
     */
    public function update(): IMessage {
        $parser  = $this->getParser();
        $builder = $this->getBuilder();
        if ($parser->isReadCompleted() === false) {
            $this->read();
        } else if ($builder->isReadCompleted() === true && $builder->isWriteCompleted() === false) {
            $this->write();
        }

        if ($parser->isReadCompleted() === true && $builder->isWriteCompleted() === true) {
            $this->setStatus(self::STATUS_COMPLETED);
        }

        return $this;
    }

}