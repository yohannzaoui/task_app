<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditProfileFormType
 *
 * @package App\Form
 */
class EditProfileFormType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => false,
                'label' => 'Nom d\'utilisateur'
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'Email'
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => 'Téléphone'
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'Adresse'
            ])
            ->add('zipCode', TextType::class, [
                'required' => false,
                'label' => 'Code postal'
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'Ville'
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'label' => 'Pays'
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'label' => 'Image de profil'
            ])
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
