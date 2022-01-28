<?php

namespace App\EventListeners\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $customersSubmenu = $event->getMenu()->getChild('customers');

        $customersSubmenu
            ->addChild('new-subitem', ['route' => 'app_admin_newsletter_index'])
            ->setLabel('sylius.menu.admin.main.newsletters.header')
            ->setLabelAttribute('icon', 'envelope open');
    }
}
