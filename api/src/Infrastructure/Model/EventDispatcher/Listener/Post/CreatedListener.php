<?php

declare(strict_types=1);

namespace App\Infrastructure\Model\EventDispatcher\Listener\Post;


use RuntimeException;
use Swift_Mailer;

class CreatedListener
{
    private Swift_Mailer $mailer;
    private array $from;

    public function __construct(Swift_Mailer $mailer, array $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function __invoke($event)
    {
        $message = (new \Swift_Message('Post created '))
            ->setFrom($this->from)
            ->setTo($event->email->getEmail())
            ->setBody($event->getMessage());

        if (!$this->mailer->send($message)) {
            throw new RuntimeException('Unable to send message.');
        }
    }
}