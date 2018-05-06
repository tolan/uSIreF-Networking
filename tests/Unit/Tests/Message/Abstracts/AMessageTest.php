<?php

namespace uSIreF\Networking\Unit\Tests\Message\Abstracts;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Interfaces\Stream\IConnection;
use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse, IParser, IBuilder};
use uSIreF\Networking\Interfaces\Message\{IMessage, IPlugin};

/**
 * This file defines test class for Abstract Message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class AMessageTest extends TestCase {

    /**
     * Success test for method addPlugin.
     *
     * @return void
     */
    public function testAddPlugin() {
        $message = $this->_createMessage()->nextStatus(IMessage::STATUS_OPEN);
        $plugin  = $this->createMock(IPlugin::class);
        $plugin->expects($this->exactly(1))
            ->method('update');

        $this->assertInstanceOf(IMessage::class, $message->addPlugin($plugin));
        $message->update()
            ->nextStatus(IMessage::STATUS_READING)
            ->update()
            ->update();
    }

    /**
     * Sucess test for method getStatus.
     *
     * @return void
     */
    public function testGetStatus() {
        $message = $this->_createMessage();
        $this->assertEquals(IMessage::STATUS_OPEN, $message->getStatus());
        $message->nextStatus(IMessage::STATUS_READING)->update();
        $this->assertEquals(IMessage::STATUS_READING, $message->getStatus());
    }

    /**
     * Success test for method getConnection.
     *
     * @return void
     */
    public function testGetConnection() {
        $message = $this->_createMessage();
        $this->assertInstanceOf(IConnection::class, $message->getConnection());
    }

    /**
     * Success test for method getRequest.
     *
     * @return void
     */
    public function testGetRequest() {
        $message = $this->_createMessage();
        $this->assertInstanceOf(IRequest::class, $message->getRequest());
    }

    /**
     * Success test for method getResponse.
     *
     * @return void
     */
    public function testGetResponse() {
        $message = $this->_createMessage();
        $this->assertInstanceOf(IResponse::class, $message->getResponse());
    }

    /**
     * Success test for method isTimeoutReached.
     *
     * @return void
     */
    public function testIsTimeoutReached() {
        $message = $this->_createMessage();
        $this->assertFalse($message->isTimeoutReached());
        // TODO set start time and test true state
    }

    /**
     * Success test for method isCompleted.
     *
     * @return void
     */
    public function testIsCompleted() {
        $message = $this->_createMessage()->nextStatus(IMessage::STATUS_OPEN);
        $this->assertFalse($message->isCompleted());
        $message->update();
        $this->assertFalse($message->isCompleted());
        $message->nextStatus(IMessage::STATUS_COMPLETED);
        $this->assertFalse($message->isCompleted());
        $message->update();
        $this->assertTrue($message->isCompleted());
    }

    /**
     * Success test for method close.
     *
     * @return void
     */
    public function testClose() {
        $connection = $this->createMock(IConnection::class);
        $connection->expects($this->exactly(1))
            ->method('close');

        $message = $this->_createMessage($connection);

        $this->assertInstanceOf(IMessage::class, $message->close());
        $this->assertEquals(IMessage::STATUS_CLOSED, $message->getStatus());
    }

    /**
     * Success test for protected method read.
     *
     * @return void
     */
    public function testRead() {
        $connection = $this->createMock(IConnection::class);
        $connection->expects($this->exactly(2))
            ->method('read')
            ->willReturn('data');

        $parser = $this->createMock(IParser::class);
        $parser->expects($this->exactly(2))
            ->method('addData')
            ->willReturnSelf();
        $parser->expects($this->exactly(2))
            ->method('isReadCompleted')
            ->willReturn(false, true);

        $message = $this->_createMessage($connection, null, null, $parser);
        $this->assertEquals(IMessage::STATUS_OPEN, $message->getStatus());
        $message->callRead();
        $this->assertEquals(IMessage::STATUS_READING, $message->getStatus());
        $message->callRead();
        $this->assertEquals(IMessage::STATUS_READ_COMPLETE, $message->getStatus());
    }

    /**
     * Success test for protected method write.
     *
     * @return void
     */
    public function testWrite() {
        $connection = $this->createMock(IConnection::class);
        $connection->expects($this->exactly(3))
            ->method('write')
            ->willReturn(10);

        $builder = $this->createMock(IBuilder::class);
        $builder->expects($this->exactly(3))
            ->method('render')
            ->willReturn('ok');
        $builder->expects($this->exactly(3))
            ->method('written');
        $builder->expects($this->exactly(3))
            ->method('isWriteCompleted')
            ->willReturn(false, false, true);

        $message = $this->_createMessage($connection, null, null, null, $builder);
        $this->assertEquals(IMessage::STATUS_OPEN, $message->getStatus());
        $message->callWrite();
        $this->assertEquals(IMessage::STATUS_WRITING, $message->getStatus());
        $message->callWrite();
        $this->assertEquals(IMessage::STATUS_WRITING, $message->getStatus());
        $message->callWrite();
        $this->assertEquals(IMessage::STATUS_WRITE_COMPLETE, $message->getStatus());
    }

    /**
     * Helper method for create message with stubs.
     *
     * @param IConnection $connection Connection instance (optional)
     * @param IRequest    $request    Request instance (optional)
     * @param IResponse   $response   Response instance (optional)
     * @param IParser     $parser     Parser instance (optional)
     * @param IBuilder    $builder    Builder instance (optional)
     *
     * @return AMessage
     */
    private function _createMessage(
        IConnection $connection = null,
        IRequest $request = null,
        IResponse $response = null,
        IParser $parser = null,
        IBuilder $builder = null
    ) {
        $connection = ($connection ?? $this->createMock(IConnection::class));
        $request    = ($request ?? $this->createMock(IRequest::class));
        $response   = ($response ?? $this->createMock(IResponse::class));
        $parser     = ($parser ?? $this->createMock(IParser::class));
        $builder    = ($builder ?? $this->createMock(IBuilder::class));

        return new AMessage($connection, $request, $response, $parser, $builder);
    }

}