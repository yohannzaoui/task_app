<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 17/03/19
 * Time: 06:49
 */

namespace App\Subscriber;


use App\Service\EmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\TaskToMyEmailEvent;
use App\Event\EmailRegisterEvent;
use App\Event\EmailPasswordEvent;

/**
 * Class EmailSubscriber
 *
 * @package App\Event
 */
class EmailSubscriber implements EventSubscriberInterface
{
    /**
     * @var \App\Service\EmailSender
     */
    private $emailSender;

    /**
     * EmailSubscriber constructor.
     *
     * @param \App\Service\EmailSender $emailSender
     */
    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TaskToMyEmailEvent::NAME  => 'onTaskToMyEmailEvent',
            EmailRegisterEvent::NAME => 'onRegisterEmailEvent',
            EmailPasswordEvent::NAME => 'onEmailPasswordEvent'
        ];
    }

    /**
     * @param \App\Event\TaskToMyEmailEvent $event
     */
    public function onTaskToMyEmailEvent(TaskToMyEmailEvent $event)
    {
        $this->emailSender->mail(
            'My Task : '.$event->getTitle(),
            $event->getEmail(),
            'Title : '.$event->getTitle().' Content : '.$event->getContent(),
            'task@task_app.com'
        );
    }

    /**
     * @param \App\Event\EmailRegisterEvent $event
     */
    public function onRegisterEmailEvent(EmailRegisterEvent $event)
    {
        $this->emailSender->mail(
            'Confimer la création de votre compte',
            $event->getUserEmail(),
            'Pour confimer la création de votre compte veuillez cliquez sur ce lien: http://127.0.0.1:8000/confirm/'.$event->getToken().'/'.$event->getId(),
            'register@shedule.com'
        );
    }

    /**
     * @param \App\Event\EmailPasswordEvent $event
     */
    public function onEmailPasswordEvent(EmailPasswordEvent $event)
    {
        $this->emailSender->mail(
            'Récuperation de votre compte',
            $event->getUserEmail(),
            'Pour récuperer votre compte veuillez cliquez sur ce lien: http://127.0.0.1:8000/confirm/password/'.$event->getToken().'/'.$event->getId(),
            'account@shedule.com'
        );
    }

}