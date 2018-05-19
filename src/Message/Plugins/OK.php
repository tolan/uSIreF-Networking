<?php

namespace uSIreF\Networking\Message\Plugins;

use uSIreF\Networking\Interfaces\Message\{IMessage, IPlugin};
use SplSubject;

/**
 * This file defines class for message plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class OK implements IPlugin {

    /**
     * Updates plugin when observed message is changed.
     *
     * @param SplSubject $subject Message instance
     *
     * @return void
     */
    public function update(SplSubject $subject): void {
        if ($subject->getStatus() === IMessage::STATUS_READ_COMPLETE) {
            $subject->getResponse()->data('OK');
        }
    }

}