<?php

namespace uSIreF\Networking\Unit\Tests\Message\Plugins;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Message\Plugins\OK;
use uSIreF\Networking\Interfaces\Message\IMessage;
use uSIreF\Networking\Interfaces\Protocol\IResponse;

/**
 * This file defines test class for OK Plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class OKTest extends TestCase {

    /**
     * Success test for method update without any action.
     *
     * @return void
     */
    public function testUpdateWithoutAction() {
        $plugin = new OK();

        $message = $this->createMock(IMessage::class);
        $message->expects($this->exactly(0))
            ->method('getResponse');
        $message->expects($this->exactly(1))
            ->method('getStatus')
            ->willReturn(IMessage::STATUS_OPEN);

        $this->assertInstanceOf(IMessage::class, $plugin->update($message));
    }

    /**
     * Success test for method update with set response message.
     *
     * @return void
     */
    public function testUpdateWithResponse() {
        $plugin = new OK();

        $response = $this->createMock(IResponse::class);
        $response->expects($this->exactly(1))
            ->method('data')
            ->with('OK');

        $message = $this->createMock(IMessage::class);
        $message->expects($this->exactly(1))
            ->method('getResponse')
            ->willReturn($response);
        $message->expects($this->exactly(1))
            ->method('getStatus')
            ->willReturn(IMessage::STATUS_READ_COMPLETE);

        $this->assertInstanceOf(IMessage::class, $plugin->update($message));
    }

}