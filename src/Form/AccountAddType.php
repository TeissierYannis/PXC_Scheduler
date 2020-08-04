<?php

namespace App\Form;

use App\Entity\PackAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('AccountUsername', TextType::class, [
                'required' => true,
                'label' => 'Account Username'
            ])
            ->add('AccountLogin', EmailType::class, [
                'required' => true,
                'label' => 'Account Login'
            ])
            ->add('AccountPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Account Password'
            ])
            ->add('Pack_Quantity', IntegerType::class, [
                'required' => true,
                'label' => 'Quantity Of Packs'
            ])
            ->add('AccountLevel', IntegerType::class, [
                'required' => true,
                'label' => 'Account Level'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PackAccount::class,
        ]);
    }
}
