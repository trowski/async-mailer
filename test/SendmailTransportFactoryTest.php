<?php

namespace Trowski\AsyncSwiftMailer\Test;

use PHPUnit\Framework\TestCase;
use Swift_SendmailTransport as SwiftSendmailTransport;
use Trowski\AsyncSwiftMailer\SendmailTransportFactory;

class SendmailTransportFactoryTest extends TestCase
{
    public function testGetKey(): void
    {
        $factory1 = new SendmailTransportFactory;
        $factory2 = new SendmailTransportFactory('/usr/local/sbin/sendmail -bs');

        $this->assertNotSame($factory1->getKey(), $factory2->getKey());
    }

    public function testCreateTransport(): void
    {
        $factory = new SendmailTransportFactory;

        /** @var SwiftSendmailTransport $transport */
        $transport = $factory->createTransport();

        $this->assertInstanceOf(SwiftSendmailTransport::class, $transport);
    }
}
