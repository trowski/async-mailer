<?php

namespace Trowski\AsyncSwiftMailer\Test;

use PHPUnit\Framework\TestCase;
use Swift_SmtpTransport as SwiftSmtpTransport;
use Trowski\AsyncSwiftMailer\SmtpTransportFactory;

class SmtpTransportFactoryTest extends TestCase
{
    public function testGetKey(): void
    {
        $factory1 = new SmtpTransportFactory;
        $factory2 = $factory1->withHost('google.com');

        $this->assertNotSame($factory1->getKey(), $factory2->getKey());
    }

    public function testCreateTransport(): void
    {
        $factory = new SmtpTransportFactory;
        $factory = $factory->withUsername('trowski');
        $factory = $factory->withPassword('password');
        $factory = $factory->withEncryption();

        /** @var SwiftSmtpTransport $transport */
        $transport = $factory->createTransport();

        $this->assertInstanceOf(SwiftSmtpTransport::class, $transport);

        $this->assertSame('trowski', $transport->getUsername());
        $this->assertSame('password', $transport->getPassword());
    }

    public function testWithHost(): void
    {
        $factory1 = new SmtpTransportFactory;
        $factory2 = $factory1->withHost('google.com');

        $this->assertNotSame($factory1, $factory2);
        $this->assertNotSame($factory1->getHost(), $factory2->getHost());
    }

    public function testWithPort(): void
    {
        $factory1 = new SmtpTransportFactory;
        $factory2 = $factory1->withPort(587);

        $this->assertNotSame($factory1, $factory2);
        $this->assertNotSame($factory1->getPort(), $factory2->getPort());
    }

    public function testWithUsername(): void
    {
        $factory1 = new SmtpTransportFactory;
        $factory2 = $factory1->withUsername('trowski');

        $this->assertNotSame($factory1, $factory2);
        $this->assertNotSame($factory1->getUsername(), $factory2->getUsername());

        $factory3 = $factory2->withoutUsername();
        $this->assertNotSame($factory2->getUsername(), $factory3->getUsername());
    }

    public function testWithPassword(): void
    {
        $factory1 = new SmtpTransportFactory;
        $factory2 = $factory1->withPassword('password');

        $this->assertNotSame($factory1, $factory2);
        $this->assertNotSame($factory1->getPassword(), $factory2->getPassword());

        $factory3 = $factory2->withoutPassword();
        $this->assertNotSame($factory2->getPassword(), $factory3->getPassword());
    }

    public function testWithEncryption(): void
    {
        $factory1 = new SmtpTransportFactory;
        $factory2 = $factory1->withEncryption();

        $this->assertNotSame($factory1, $factory2);
        $this->assertNotSame($factory1->isEncryptionEnabled(), $factory2->isEncryptionEnabled());

        $factory3 = $factory2->withoutEncryption();
        $this->assertNotSame($factory2->isEncryptionEnabled(), $factory3->isEncryptionEnabled());
        $this->assertSame($factory1->isEncryptionEnabled(), $factory3->isEncryptionEnabled());
    }

    public function testWithLocalDomain(): void
    {
        $factory1 = new SmtpTransportFactory;
        $factory2 = $factory1->withLocalDomain('google.com');

        $this->assertNotSame($factory1, $factory2);
        $this->assertNotSame($factory1->getLocalDomain(), $factory2->getLocalDomain());
    }

    public function testWithAuthMode(): void
    {
        $factory1 = new SmtpTransportFactory;
        $factory2 = $factory1->withAuthMode('MD5');

        $this->assertNotSame($factory1, $factory2);
        $this->assertNotSame($factory1->getAuthMode(), $factory2->getAuthMode());
    }
}
