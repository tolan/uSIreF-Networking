<?php

namespace uSIreF\Networking\Unit\Tests\Message\Plugins;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Message\Plugins\Status;
use uSIreF\Networking\Interfaces\Message\IMessage;

/**
 * This file defines test class for Status Plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class StatusTest extends TestCase {

    /**
     * Success test for method notify.
     *
     * @return void
     */
    public function testNotify() {
        $plugin = new Status();
        $data   = [
            IMessage::STATUS_OPEN,
            IMessage::STATUS_OPEN,
            IMessage::STATUS_READING,
            IMessage::STATUS_READING,
            IMessage::STATUS_READING,
            IMessage::STATUS_READ_COMPLETE,
            IMessage::STATUS_WRITING,
            IMessage::STATUS_WRITING,
            IMessage::STATUS_WRITE_COMPLETE,
            IMessage::STATUS_COMPLETED,
            IMessage::STATUS_COMPLETED,
            IMessage::STATUS_CLOSED,
        ];

        $message = $this->createMock(IMessage::class);
        $message->expects($this->any())
            ->method('getStatus')
            ->willReturn(...$data);

        $this->assertNull($plugin->update($message));
        $len = count($data);
        for ($i = 1; $i < $len; $i++) {
            $this->assertNull($plugin->update($message));
            $this->assertTrue(($plugin->isChanged() === ($data[$i] !== $data[($i - 1)])));
        }
    }

    /**
     * Success test for method getMessage.
     *
     * @return void
     */
    public function testGetMessage() {
        $plugin  = new Status();
        $message = $this->createMock(IMessage::class);

        $this->assertNull($plugin->update($message));
        $this->assertSame($message, $plugin->getMessage());
    }

    /**
     * Success test for method __clone.
     *
     * @return void
     */
    public function testClone() {
        $plugin  = new Status();
        $message = $this->createMock(IMessage::class);
        $message->expects($this->any())
            ->method('getStatus')
            ->willReturn(IMessage::STATUS_READING);

        $plugin->update($message);
        $this->assertTrue($plugin->isChanged());
        $this->assertSame($message, $plugin->getMessage());

        $clone = clone $plugin;

        $this->assertFalse($clone->isChanged());
        $this->assertNull($clone->getMessage());
    }

}