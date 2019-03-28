<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 17/03/19
 * Time: 06:49
 */

namespace App\Subscriber;


use App\Event\TaskByEmailEvent;
use App\Helper\Email;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\TaskToMyEmailEvent;
use App\Event\EmailRegisterEvent;
use App\Event\EmailPasswordEvent;

/**
 * Class EmailSubscriber
 *
 * @package App\Subscriber
 */
class EmailSubscriber implements EventSubscriberInterface
{

    /**
     * @var \App\Helper\Email
     */
    private $email;


    /**
     * EmailSubscriber constructor.
     *
     * @param \App\Helper\Email $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TaskToMyEmailEvent::NAME  => 'onTaskToMyEmailEvent',
            EmailRegisterEvent::NAME => 'onRegisterEmailEvent',
            EmailPasswordEvent::NAME => 'onEmailPasswordEvent',
            TaskByEmailEvent::NAME => 'onTaskByEmail'
        ];
    }

    /**
     * @param \App\Event\TaskToMyEmailEvent $event
     */
    public function onTaskToMyEmailEvent(TaskToMyEmailEvent $event)
    {
        $this->email->taskToMyEmail(
            $event->getEmail(),
            $event->getTitle(),
            $event->getContent()
        );
    }

    /**
     * @param \App\Event\EmailRegisterEvent $event
     */
    public function onRegisterEmailEvent(EmailRegisterEvent $event)
    {
        $this->email->registerEmail(
            $event->getUserEmail(),
            $event->getToken(),
            $event->getId()
        );
    }

    /**
     * @param \App\Event\EmailPasswordEvent $event
     */
    public function onEmailPasswordEvent(EmailPasswordEvent $event)
    {
        $this->email->emailPassword(
            $event->getUserEmail(),
            $event->getToken(),
            $event->getId()
        );
    }

    /**
     * @param \App\Event\TaskByEmailEvent $event
     */
    public function onTaskByEmail(TaskByEmailEvent $event)
    {
        $this->email->taskByEmail(
            $event->getEmail(),
            $event->getTitle(),
            $event->getContent()
        );
    }

}