<?php

namespace Trowski\AsyncSwiftMailer\Test;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Pool;
use Amp\Parallel\Worker\Task;
use Amp\PHPUnit\AsyncTestCase;
use Amp\Promise;
use Swift_Message as SwiftMessage;
use Swift_Transport as SwiftTransport;
use Trowski\AsyncSwiftMailer\Mailer;
use Trowski\AsyncSwiftMailer\TransportFactory;
use function Amp\call;

class MailerTest extends AsyncTestCase
{
    public function testSend(): \Generator
    {
        $message = $this->createMock(SwiftMessage::class);

        $transport = $this->createMock(SwiftTransport::class);
        $transport->expects($this->once())
            ->method('send')
            ->with($message);

        $factory = $this->createMock(TransportFactory::class);
        $factory->expects($this->once())
            ->method('createTransport')
            ->willReturn($transport);

        $pool = $this->createMock(Pool::class);
        $pool->expects($this->once())
            ->method('enqueue')
            ->willReturnCallback(function (Task $task): Promise {
                return call([$task, 'run'], $this->createMock(Environment::class));
            });

        $mailer = new Mailer($factory, $pool);

        $failed = yield $mailer->send($message);

        $this->assertIsArray($failed);
        $this->assertEmpty($failed);
    }
}
