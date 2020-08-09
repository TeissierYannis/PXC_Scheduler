<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\PackAccount;
use App\Repository\PackAccountRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $options['user'];

        $builder
            ->add('account', EntityType::class, [
                'class' => PackAccount::class,
                'choice_label' => 'AccountUsername',
                'query_builder' => function(PackAccountRepository $pcr) use ($user) {
                    return $pcr->createQueryBuilder('p')
                        ->where('p.UserId = :user')
                        ->setParameter('user', $user);
                },
                'label' => 'Account'
            ])
            ->add('scheduler_datetime', DateTimeType::class, [
                'label' => 'Date & Time',
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'user' => null
        ]);
    }
}
