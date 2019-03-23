<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 17/03/19
 * Time: 09:08
 */

namespace App\Subscriber;

use App\Event\FileRemoverEvent;
use App\Event\FileUploaderEvent;
use App\Service\FileRemover;
use App\Service\FileUploader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class FileSubscriber
 *
 * @package App\Subscriber
 */
class FileSubscriber implements EventSubscriberInterface
{
    /**
     * @var \App\Service\FileRemover
     */
    private $fileRemover;

    private $fileUploader;

    /**
     * FileSubscriber constructor.
     *
     * @param \App\Service\FileRemover  $fileRemover
     * @param \App\Service\FileUploader $fileUploader
     */
    public function __construct(FileRemover $fileRemover, FileUploader $fileUploader)
    {
        $this->fileRemover = $fileRemover;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FileRemoverEvent::NAME => 'onFileRemoverEvent'

        ];
    }

    /**
     * @param \App\Event\FileRemoverEvent $event
     */
    public function onFileRemoverEvent(FileRemoverEvent $event)
    {
        $this->fileRemover->deleteFile($event->getFile());
    }

}