<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Sylius\Bundle\CustomerBundle\Form\Type\CustomerProfileType;

final class CustomerProfileTypeExtension extends AbstractCustomerExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [CustomerProfileType::class];
    }
}
