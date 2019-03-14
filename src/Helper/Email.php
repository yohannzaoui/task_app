<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/02/19
 * Time: 18:41
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
     * @param $title
     * @param $content
     * @param $email
     * @param $myEmail
     */
    public function taskByEmail($title, $content, $email, $myEmail)
    {
        $this->emailSender->mail(
            'I send my task'.$title,
            $email,
            'Title : '.$title.' Content : '.$content,
            $myEmail
        );
    }

    /**
     * @param $userMail
     * @param $token
     * @param $id
     */
    public function emailRegister($userMail, $token, $id)
    {
        $this->emailSender->mail(
            'Confimer la création de votre compte',
            $userMail,
            'Pour confimer la création de votre compte veuillez cliquez sur ce lien: http://127.0.0.1:8000/confirm/'.$token.'/'.$id,
            'register@shedule.com'
        );
    }

    /**
     * @param $userMail
     * @param $token
     * @param $id
     */
    public function emailPassword($userMail, $token, $id)
    {
        $this->emailSender->mail(
            'Récuperation de votre compte',
            $userMail,
            'Pour récuperer votre compte veuillez cliquez sur ce lien: http://127.0.0.1:8000/confirm/password/'.$token.'/'.$id,
            'account@shedule.com'
        );
    }
}