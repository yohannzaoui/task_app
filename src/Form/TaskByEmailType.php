<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 11/03/19
 * Time: 21:16
 */

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class TaskByEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre'
            ])
            ->add('content', TextareaType::class,[
                'required' => true,
                'label' => 'Déscription'
            ])
        ;
    }
}