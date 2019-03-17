<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 17/03/19
 * Time: 09:12
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

/**
 * Class FileRemoverEvent
 *
 * @package App\Event
 */
class FileRemoverEvent extends Event
{
    /**
     *
     */
    const NAME = 'fileRemover.event';

    /**
     * @var string|null
     */
    private $file;


    /**
     * FileRemoverEvent constructor.
     *
     * @param string|null $file
     */
    public function __construct(? string $file)
    {
        $this->file = $file;
    }


    /**
     * @return string|null
     */
    public function getFile(): ? string
    {
        return $this->file;
    }
}