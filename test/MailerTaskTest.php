<?php

namespace Trowski\AsyncSwiftMailer\Test;

use Amp\Parallel\Worker\BasicEnvironment;
use PHPUnit\Framework\TestCase;
use Swift_Message as SwiftMessage;
use Swift_Transport as SwiftTransport;
use Trowski\AsyncSwiftMailer\MailerTask;
use Trowski\AsyncSwiftMailer\TransportFactory;

class MailerTaskTest extends TestCase
{
    public function testTransportReused(): void
    {
        $message = $this->createMock(SwiftMessage::class);

        $transport = $this->createMock(SwiftTransport::class);
        $transport->expects($this->exactly(2))
            ->method('send')
            ->with($message);

        $transport->expects($this->once())
            ->method('ping')
            ->willReturn(true);

        $factory = $this->createMock(TransportFactory::class);
        $factory->expects($this->once())
            ->method('createTransport')
            ->willReturn($transport);

        $task = new MailerTask($factory, $message);

        $environment = new BasicEnvironment;

        $failed = $task->run($environment);
        $failed = $task->run($environment);
    }

    public function testTransportReplacedIfPingFails(): void
    {
        $message = $this->createMock(SwiftMessage::class);

        $transport1 = $this->createMock(SwiftTransport::class);
        $transport1->expects($this->once())
            ->method('send')
            ->with($message);

        $transport1->expects($this->once())
            ->method('ping')
            ->willReturn(false);

        $transport2 = $this->createMock(SwiftTransport::class);
        $transport2->expects($this->once())
            ->method('send')
            ->with($message);

        $factory = $this->createMock(TransportFactory::class);
        $factory->expects($this->exactly(2))
            ->method('createTransport')
            ->willReturnOnConsecutiveCalls($transport1, $transport2);

        $task = new MailerTask($factory, $message);

        $environment = new BasicEnvironment;

        $failed = $task->run($environment);
        $failed = $task->run($environment);
    }

    public function testTransportWithSameKeys(): void
    {
        $message = $this->createMock(SwiftMessage::class);

        $transport = $this->createMock(SwiftTransport::class);
        $transport->expects($this->exactly(2))
            ->method('send')
            ->with($message);

        $transport->expects($this->once())
            ->method('ping')
            ->willReturn(true);

        $factory = $this->createMock(TransportFactory::class);
        $factory->expects($this->once())
            ->method('createTransport')
            ->willReturn($transport);

        $factory->expects($this->exactly(2))
            ->method('getKey')
            ->willReturn('Factory');

        $task1 = new MailerTask($factory, $message);
        $task2 = new MailerTask($factory, $message);

        $environment = new BasicEnvironment;

        $failed = $task1->run($environment);
        $failed = $task2->run($environment);
    }

    public function testTransportWithDifferentKeys(): void
    {
        $message = $this->createMock(SwiftMessage::class);

        $transport1 = $this->createMock(SwiftTransport::class);
        $transport1->expects($this->once())
            ->method('send')
            ->with($message);

        $transport1->expects($this->never())
            ->method('ping');

        $transport2 = $this->createMock(SwiftTransport::class);
        $transport2->expects($this->once())
            ->method('send')
            ->with($message);

        $factory = $this->createMock(TransportFactory::class);
        $factory->expects($this->exactly(2))
            ->method('createTransport')
            ->willReturnOnConsecutiveCalls($transport1, $transport2);

        $factory->expects($this->exactly(2))
            ->method('getKey')
            ->willReturnOnConsecutiveCalls('1', '2');

        $task1 = new MailerTask($factory, $message);
        $task2 = new MailerTask($factory, $message);

        $environment = new BasicEnvironment;

        $failed = $task1->run($environment);
        $failed = $task2->run($environment);
    }
}
