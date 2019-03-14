<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 14/03/19
 * Time: 08:12
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class TwigExtension
 *
 * @package App\Twig
 */
class TwigExtension extends AbstractExtension
{
    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('h1center', [$this, 'h1Center'], ['is_safe' => ['html']]),
            new TwigFilter('h1', [$this, 'h1'], ['is_safe' => ['html']]),
            new TwigFilter('h3error', [$this, 'h3Error'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function h1Center($text)
    {
        return '<h1 class="center">'.$text.'</h1>';
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function h1($text)
    {
        return '<h1>'.$text.'</h1>';
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function h3Error($text)
    {
        return '<h3 class="error">'.$text.'</h3>';
    }
}