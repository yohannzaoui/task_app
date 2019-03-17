<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 17/03/19
 * Time: 08:12
 */

namespace App\Helper;


use App\Service\EmailSender;

/**
 * Class Email
 *
 * @package App\Helper
 */
class Email
{
    /**
     * @var \App\Service\EmailSender
     */
    private $emailSender;

    /**
     * Email constructor.
     *
     * @param \App\Service\EmailSender $emailSender
     */
    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * @param $email
     * @param $title
     * @param $content
     */
    public function taskToMyEmail($email, $title, $content)
    {
        $this->emailSender->mail(
            'My Task : '.$title,
            $email,
            'Title : '.$title.' Content : '.$content,
            'task@task_app.com'
        );
    }

    /**
     * @param $email
     * @param $title
     * @param $content
     */
    public function taskByEmail($email, $title, $content)
    {
        $this->emailSender->mail(
            'I share my Task : '.$title,
            $email,
            'Title : '.$title.' Content : '.$content,
            'task@task_app.com'
        );
    }

    /**
     * @param $email
     * @param $token
     * @param $id
     */
    public function registerEmail($email, $token, $id)
    {
        $this->emailSender->mail(
            'Confimer la création de votre compte',
            $email,
            'Pour confimer la création de votre compte veuillez cliquez sur ce lien: http://127.0.0.1:8000/confirm/'.$token.'/'.$id,
            'register@shedule.com'
        );
    }


    /**
     * @param $email
     * @param $token
     * @param $id
     */
    public function emailPassword($email, $token, $id)
    {
        $this->emailSender->mail(
            'Récuperation de votre compte',
            $email,
            'Pour récuperer votre compte veuillez cliquez sur ce lien: http://127.0.0.1:8000/confirm/password/'.$token.'/'.$id,
            'account@shedule.com'
        );
    }
}