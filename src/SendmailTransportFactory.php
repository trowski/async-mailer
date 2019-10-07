<?php

namespace Trowski\AsyncSwiftMailer;

use Swift_SendmailTransport as SwiftSendmailTransport;
use Swift_Transport as SwiftTransport;

final class SendmailTransportFactory implements TransportFactory
{
    private $command;

    public function __construct(string $command = '/usr/sbin/sendmail -bs')
    {
        $this->command = $command;
    }

    public function createTransport(): SwiftTransport
    {
        return new SwiftSendmailTransport($this->command);
    }

    public function getKey(): string
    {
        return \sha1($this->command);
    }
}
