<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/02/19
 * Time: 20:56
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EmailFormType
 *
 * @package App\Form
 */
class EmailFormType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Your email'
            ]);
    }
}