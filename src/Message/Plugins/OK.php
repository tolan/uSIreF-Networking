<?php

namespace uSIreF\Networking\Message\Plugins;

use uSIreF\Networking\Interfaces\Message\{IMessage, IPlugin};

/**
 * This file defines class for message plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class OK implements IPlugin {

    /**
     * Updates plugin when observed message is changed.
     *
     * @param IMessage $message Message instance
     *
     * @return IMessage
     */
    public function update(IMessage $message): IMessage {
        if ($message->getStatus() === IMessage::STATUS_READ_COMPLETE) {
            $message->getResponse()->data('OK');
        }

        return $message;
    }

}