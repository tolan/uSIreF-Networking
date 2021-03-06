<?php

namespace uSIreF\Networking\Unit\Tests\Message;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Message\Incomming;
use uSIreF\Networking\Interfaces\Stream\IConnection;
use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse, IParser, IBuilder};

/**
 * This file defines test class for Incomming message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class IncommingTest extends TestCase {

    /**
     * Success test for method update on incomming message.
     *
     * @return void
     */
    public function testUpdate() {
        $connection = $this->createMock(IConnection::class);
        $connection->expects($this->any())
            ->method('read')
            ->willReturn('bad request');
        $connection->expects($this->any())
            ->method('write')
            ->willReturn(4);

        $request  = $this->createMock(IRequest::class);
        $response = $this->createMock(IResponse::class);

        $parser = $this->createMock(IParser::class);
        $parser->expects($this->any())
            ->method('isReadCompleted')
            ->willReturn(false, false, false, false, true, true, true, true, true, true, true, true);
        $parser->expects($this->any())
            ->method('addData')
            ->willReturnSelf();

        $builder = $this->createMock(IBuilder::class);
        $builder->expects($this->any())
            ->method('isReadCompleted')
            ->willReturn(true);
        $builder->expects($this->any())
            ->method('isWriteCompleted')
            ->willReturn(false, false, false, false, true, true, true, true);
        $builder->expects($this->any())
            ->method('render')
            ->willReturn('data');

        $message = new Incomming($connection, $request, $response, $parser, $builder);

        $flow = [
            Incomming::STATUS_OPEN,
            Incomming::STATUS_READING,
            Incomming::STATUS_READ_COMPLETE,
            Incomming::STATUS_WRITING,
            Incomming::STATUS_COMPLETED,
        ];

        foreach ($flow as $expected) {
            $this->assertEquals($expected, $message->getStatus());
            $message->notify();
        }
    }

}