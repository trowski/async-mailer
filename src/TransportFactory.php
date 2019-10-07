<?php

namespace Trowski\AsyncSwiftMailer;

use Swift_Transport as SwiftTransport;

interface TransportFactory
{
    /**
     * @return SwiftTransport Transport used to create a Swift Mailer object to send mail.
     */
    public function createTransport(): SwiftTransport;

    /**
     * @return string Unique key for the transport created by this factory.
     */
    public function getKey(): string;
}
