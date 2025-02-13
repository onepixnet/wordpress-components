<?php

declare(strict_types=1);

namespace OnePix\WordPressContractsImplementation;

use OnePix\WordPressContracts\AdminPage;

function get_admin_page_hook_name(AdminPage $adminPage): string
{
    return get_plugin_page_hookname($adminPage->getMenuSlug(), $adminPage->getParentSlug() ?? '');
}
