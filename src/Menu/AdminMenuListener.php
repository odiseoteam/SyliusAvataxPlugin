<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        /** @var ItemInterface $item */
        $item = $menu->getChild('configuration');
        if (null == $item) {
            $item = $menu;
        }

        $item->addChild('avatax', ['route' => 'odiseo_sylius_avatax_plugin_admin_avatax_configuration_index'])
            ->setLabel('odiseo_sylius_avatax_plugin.menu.admin.avatax')
            ->setLabelAttribute('icon', 'cog')
        ;
    }
}
