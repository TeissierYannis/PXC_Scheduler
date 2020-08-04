<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordEditingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'Old Password'
            ])
            ->add('new_password', PasswordType::class, [
                'required' => true,
                'label' => 'New Password',
                'mapped' => false
            ])
            ->add('repeat_password', PasswordType::class, [
                'required' => true,
                'label' => 'Repeat Password',
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
