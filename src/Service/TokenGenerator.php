<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/02/19
 * Time: 18:25
 */

namespace App\Service;


/**
 * Class TokenGenerator
 *
 * @package App\Service
 */
class TokenGenerator
{
    /**
     * @return string
     */
    static public function generate(): string
    {
        return md5(uniqid());
    }
}