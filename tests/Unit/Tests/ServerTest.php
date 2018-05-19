<?php

namespace uSIreF\Networking\Unit\Tests;

use uSIreF\Networking\Unit\Abstracts\TestCase;
use uSIreF\Networking\Server;
use uSIreF\Networking\Interfaces\{Message\IMessage, Stream\IServer};
use uSIreF\Networking\Exception;

/**
 * This file defines test class for networking Server.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class ServerTest extends TestCase {

    /**
     * Success test for method run.
     *
     * @return void
     */
    public function testRunSimple() {
        $stream   = $this->createMock(IServer::class);
        $messageA = $this->createMock(IMessage::class);
        $messageB = $this->createMock(IMessage::class);

        $stream->expects($this->exactly(2))
            ->method('stop')
            ->willReturnSelf();

        $stream->expects($this->exactly(1))
            ->method('start')
            ->willReturnSelf();

        $call = 0;
        $stream->expects($this->exactly(5))
            ->method('select')
            ->willReturnCallback(function() use (&$call, $messageA, $messageB) {
                $value = null;
                $call++;
                switch ($call) {
                    case 2:
                        $value = $messageA;
                        break;
                    case 3:
                        $value = $messageB;
                        break;
                    case 5:
                        throw new Exception('Test exception');
                }

                return $value;
            });

        $messageA->expects($this->exactly(2))
            ->method('notify')
            ->willReturnSelf();

        $messageA->expects($this->exactly(2))
            ->method('isCompleted')
            ->willReturn(false, true);

        $server = new Server($stream);

        $this->expectExceptionMessage('Test exception');
        $server->run();
    }

    /**
     * Success test for method flushErrors.
     *
     * @return void
     */
    public function testFlushErrors() {
        $stream  = $this->createMock(IServer::class);
        $message = $this->createMock(IMessage::class);
        $exception = new Exception();

        $stream->expects($this->any())
            ->method('select')
            ->willReturn($message);

        $message ->expects($this->exactly(2))
            ->method('notify')
            ->willThrowException($exception);

        $server = new Server($stream);

        $call = 0;
        $server->run(function() use (&$call) {
            ++$call;
            return ($call < 3);
        });

        $errors = $server->flushErrors();
        $this->assertCount(2, $errors);
        $this->assertContains($exception, $errors);
        $this->assertEmpty($server->flushErrors());
    }

}