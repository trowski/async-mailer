<?php

namespace Trowski\AsyncSwiftMailer;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use Swift_Mailer as SwiftMailer;
use Swift_Message as SwiftMessage;
use Swift_Transport as SwiftTransport;

final class MailerTask implements Task
{
    /** @var TransportFactory */
    private $factory;

    /** @var SwiftMessage */
    private $message;

    public function __construct(TransportFactory $factory, SwiftMessage $message)
    {
        $this->factory = $factory;
        $this->message = $message;
    }

    public function run(Environment $environment): array
    {
        $mailer = new SwiftMailer($this->getTransport($environment));
        $mailer->send($this->message, $failed);
        return $failed;
    }

    public function getTransport(Environment $environment): SwiftTransport
    {
        $key = \get_class($this->factory) . '#' . $this->factory->getKey();

        if ($environment->exists($key)) {
            $transport = $environment->get($key);
            \assert($transport instanceof SwiftTransport, 'Inconsistent state in transport storage');

            if ($transport->ping()) {
                return $transport;
            }
        }

        $transport = $this->factory->createTransport();
        $environment->set($key, $transport);
        return $transport;
    }
}
