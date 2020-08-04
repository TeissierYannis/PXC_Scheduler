<?php

namespace App\Form;

use App\Entity\PackAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('AccountUsername')
            ->add('AccountLogin')
            ->add('AccountPassword')
            ->add('Pack_Quantity')
            ->add('Packs_name')
            ->add('AccountLevel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PackAccount::class,
        ]);
    }
}
