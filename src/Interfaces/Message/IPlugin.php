<?php

namespace uSIreF\Networking\Interfaces\Message;

use SplObserver;
use SplSubject;

/**
 * This file defines interface for message plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IPlugin extends SplObserver {

    /**
     * Updates plugin when observed message is changed.
     *
     * @param SplSubject $subject Message instance
     *
     * @return void
     */
    public function update(SplSubject $subject): void;

}