<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class MailerService {
    private $replyTo;
    private $logger;

    public function __construct(private MailerInterface $mailer, string $replyTo, LoggerInterface $logger) {
        $this->mailer = $mailer;
        $this->replyTo = $replyTo;
        $this->logger = $logger;
    }

    public function sendEmail(string $to = 'nasira3795@gmail.com', 
                              string $content = '<p>See Twig integration for better HTML integration!</p>',
                              string $subject = 'A user has been edited!'): void {
        $email = (new Email())
            ->from('nasira3795@gmail.com')
            ->to($to)
            ->replyTo($this->replyTo)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

        $this->logger->info('Sending email', ['to' => $to, 'subject' => $subject]);

        $this->mailer->send($email);
    }
}
