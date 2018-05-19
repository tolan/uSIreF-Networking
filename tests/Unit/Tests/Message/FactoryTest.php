<?php

namespace uSIreF\Networking\Unit\Tests\Message;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Message\{Factory, Incomming, Outgoing};
use uSIreF\Networking\Interfaces\{Protocol, Message, Stream};

/**
 * This file defines test class for Message factory.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class FactoryTest extends TestCase {

    /**
     * Success test for method addPlugin.
     *
     * @return void
     */
    public function testAddPlugin() {
        $factory = $this->_creaetFactory();
        $plugin  = $this->createMock(Message\IPlugin::class);
        $plugin->expects($this->exactly(1))
            ->method('update');

        $this->assertInstanceOf(Factory::class, $factory->addPlugin($plugin));

        $connection = $this->createMock(Stream\IConnection::class);
        $factory->createIncomming($connection)->notify();
    }

    /**
     * Success test for method createIncomming.
     *
     * @return void
     */
    public function testCreateIncomming() {
        $factory    = $this->_creaetFactory();
        $connection = $this->createMock(Stream\IConnection::class);
        $connection->expects($this->exactly(1))
            ->method('close');
        $message    = $factory->createIncomming($connection);

        $this->assertInstanceOf(Incomming::class, $message);
        $this->assertInstanceOf(Protocol\IRequest::class, $message->getRequest());
        $this->assertInstanceOf(Protocol\IResponse::class, $message->getResponse());
        $this->assertEquals(Message\IMessage::STATUS_OPEN, $message->getStatus());
        $this->assertFalse($message->isCompleted());
        $this->assertFalse($message->isTimeoutReached());
        $this->assertInstanceOf(Message\IMessage::class, $message->close());
    }

    /**
     * Success test for method createOutgoing.
     *
     * @return void
     */
    public function testCreateOutgoing() {
        $factory    = $this->_creaetFactory();
        $connection = $this->createMock(Stream\IConnection::class);
        $connection->expects($this->exactly(1))
            ->method('close');
        $message    = $factory->createOutgoing($connection);

        $this->assertInstanceOf(Outgoing::class, $message);
        $this->assertInstanceOf(Protocol\IRequest::class, $message->getRequest());
        $this->assertInstanceOf(Protocol\IResponse::class, $message->getResponse());
        $this->assertEquals(Message\IMessage::STATUS_OPEN, $message->getStatus());
        $this->assertFalse($message->isCompleted());
        $this->assertFalse($message->isTimeoutReached());
        $this->assertInstanceOf(Message\IMessage::class, $message->close());
    }

    /**
     * Returns Factory instance with mocked protocol factory.
     *
     * @return Factory
     */
    private function _creaetFactory() {
        $protocolFac = $this->createMock(Protocol\IFactory::class);

        return new Factory($protocolFac);
    }

}