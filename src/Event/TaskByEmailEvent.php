<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 17/03/19
 * Time: 06:45
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

/**
 * Class TaskByEmailEvent
 *
 * @package App\Event
 */
class TaskByEmailEvent extends Event
{
    /**
     *
     */
    const NAME = "taskByEmail.event";

    /**
     * @var
     */
    private $email;

    /**
     * @var
     */
    private $title;

    /**
     * @var
     */
    private $content;

    /**
     * TaskToMyEmailEvent constructor.
     *
     * @param $email
     * @param $title
     * @param $content
     */
    public function __construct($email, $title, $content)
    {
        $this->email = $email;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }


}