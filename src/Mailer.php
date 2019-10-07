<?php

namespace Trowski\AsyncSwiftMailer;

use Amp\Parallel\Worker;
use Amp\Parallel\Worker\Pool;
use Amp\Promise;
use Swift_Message as SwiftMessage;

final class Mailer
{
    /** @var TransportFactory */
    private $transportFactory;

    /** @var Pool */
    private $pool;

    public function __construct(TransportFactory $transportFactory, ?Pool $pool = null)
    {
        $this->transportFactory = $transportFactory;
        $this->pool = $pool ?? Worker\pool();
    }

    /**
     * @param SwiftMessage $message
     *
     * @return Promise<string[]> Resolves with an array of failed recipients. See Swift_Mailer::send().
     */
    public function send(SwiftMessage $message): Promise
    {
        return $this->pool->enqueue(new MailerTask($this->transportFactory, $message));
    }
}
