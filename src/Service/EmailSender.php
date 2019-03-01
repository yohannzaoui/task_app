<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/02/19
 * Time: 18:35
 */

namespace App\Service;

use Swift_Message;

/**
 * Class EmailSender
 *
 * @package App\Service
 */
class EmailSender
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Emailer constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $subject
     * @param array $from
     * @param $to
     * @param $body
     * @return mixed|void
     */
    public function mail($subject, $to, $body, $from = [])
    {
        $message = (new Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body);
        $this->mailer->send($message);
    }
}