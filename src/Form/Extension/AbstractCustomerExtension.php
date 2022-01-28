<?php

namespace App\Form\Extension;

use App\Entity\Newsletter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractCustomerExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newsletters', EntityType::class, [
                'class' => Newsletter::class,
                'choice_label' => 'subject',
                'multiple' => true,
                'expanded'=>true
            ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [];
    }
}
