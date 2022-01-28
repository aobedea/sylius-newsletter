<?php

namespace App\Form\Type;

use App\Entity\Customer\Customer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class)
            ->add('content', TextareaType::class)
            ->add('customers', EntityType::class, [
                'class' => Customer::class,
                'label' => 'app.ui.subscribers',
                'choice_label' => 'user.email',
                'multiple' => true,
                'expanded'=>true,
                'disabled' => true,
                'attr' => ['class' => 'subscribers_list'],
            ])
        ;
    }
}
