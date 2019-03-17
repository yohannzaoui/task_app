<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 17/03/19
 * Time: 07:19
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

/**
 * Class EmailPasswordEvent
 *
 * @package App\Event
 */
class EmailPasswordEvent extends Event
{
    /**
     *
     */
    const NAME = "passwordEmail.event";

    /**
     * @var
     */
    private $userEmail;

    /**
     * @var
     */
    private $token;

    /**
     * @var
     */
    private $id;

    /**
     * EmailPasswordEvent constructor.
     *
     * @param $userEmail
     * @param $token
     * @param $id
     */
    public function __construct(
        $userEmail,
        $token,
        $id
    ){
        $this->userEmail = $userEmail;
        $this->token = $token;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


}