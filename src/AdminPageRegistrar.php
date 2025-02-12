<?php

declare(strict_types=1);

namespace OnePix\WordPressContractsImplementation;

use OnePix\WordPressContracts\AdminPage;

final class AdminPageRegistrar implements \OnePix\WordPressContracts\AdminPageRegistrar
{
    public function addPage(AdminPage $adminPage): void
    {
        $adminPage->getParentSlug() === null ?
            add_menu_page(
                $adminPage->getPageTitle(),
                $adminPage->getMenuTitle(),
                $adminPage->getCapability(),
                $adminPage->getMenuSlug(),
                $adminPage->printContent(...),
                $adminPage->getIconUrl() ?? '',
                $adminPage->getPosition()
            ) :
            add_submenu_page(
                $adminPage->getParentSlug(),
                $adminPage->getPageTitle(),
                $adminPage->getMenuTitle(),
                $adminPage->getCapability(),
                $adminPage->getMenuSlug(),
                $adminPage->printContent(...),
                $adminPage->getPosition()
            );
    }
}
