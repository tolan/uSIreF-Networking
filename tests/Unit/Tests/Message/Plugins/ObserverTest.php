<?php

namespace uSIreF\Networking\Unit\Tests\Message\Plugins;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Message\Plugins\Observer;
use uSIreF\Networking\Interfaces\Message\IMessage;
use SplObserver;

/**
 * This file defines test class for Observer Plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ObserverTest extends TestCase {

    /**
     * Success test for method notify.
     *
     * @return void
     */
    public function testUpdate() {
        $observer = $this->createMock(SplObserver::class);
        $plugin   = new Observer($observer);
        $message  = $this->createMock(IMessage::class);

        $observer->expects($this->exactly(1))
            ->method('update')
            ->with($message);

        $plugin->update($message);
    }

}