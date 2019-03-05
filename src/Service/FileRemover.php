<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 05/03/19
 * Time: 18:19
 */

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FileRemover
 *
 * @package App\Service
 */
class FileRemover
{
    /**
     * @var Filesystem
     */
    private $fileSystem;
    /**
     * @var
     */
    private $path;
    /**
     * FileRemover constructor.
     * @param Filesystem $fileSystem
     * @param $path
     */
    public function __construct(
        Filesystem $fileSystem,
        $path
    ){
        $this->fileSystem = $fileSystem;
        $this->path = $path;
    }
    /**
     * @param string $file
     * @return mixed|void
     */
    public function deleteFile(?string $file)
    {
        if (is_file($this->path.'/'.$file)) {
            $this->fileSystem->remove($this->path.'/'.$file);
        }
    }
}