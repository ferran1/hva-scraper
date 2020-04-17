<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class MailService
{

    public function sendMail(array $subjectAndMessage) {

        // TODO: templated email for twig file does not work? Error:
        //  "A message must have a text or an HTML part or attachments."

//            $email = (new TemplatedEmail())
//                ->htmlTemplate('mails/mailing.html.twig')
//                ->from('ferran1004@gmail.com')
//                ->to(new Address('ferran1004@gmail.com'))
//                ->subject($subjectAndMessage[0])
//                ->context([
//                    'test' => "hello",
//                ])
//                ->text("l")
////                ->text($subjectAndMessage[1])
            ;

        $email = (new Email())
            ->from('ferran1004@gmail.com')
            ->to(new Address('ferran1004@gmail.com'))
            ->subject($subjectAndMessage[0])
            ->text($subjectAndMessage[1]);

            $transport = new GmailSmtpTransport($_ENV['EMAIL'], $_ENV['PASS']);
            $mailer = new Mailer($transport);
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {;
                dd($e);
            }

        dd("Successful");
    }
}
