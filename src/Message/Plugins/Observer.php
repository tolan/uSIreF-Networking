<?php

namespace uSIreF\Networking\Message\Plugins;

use uSIreF\Networking\Interfaces\Message\IPlugin;
use SplObserver;
use SplSubject;

/**
 * This file defines class for message plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Observer implements IPlugin {

    /**
     * Observer instance.
     *
     * @var SplObserver
     */
    private $_observer = null;

    /**
     * Construct method for inject observer.
     *
     * @param SplObserver $observer Observer instance.
     *
     * @return void
     */
    public function __construct(SplObserver $observer) {
        $this->_observer = $observer;
    }

    /**
     * Updates plugin when observed message is changed.
     *
     * @param SplSubject $subject Message instance
     *
     * @return void
     */
    public function update(SplSubject $subject): void {
        $this->_observer->update($subject);
    }

}