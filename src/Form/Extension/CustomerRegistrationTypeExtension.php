<?php

namespace App\Form\Extension;

use Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerRegistrationType;

final class CustomerRegistrationTypeExtension extends AbstractCustomerExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [CustomerRegistrationType::class];
    }
}
