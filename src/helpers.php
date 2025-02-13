<?php

declare(strict_types=1);

namespace OnePix\WordPressContractsImplementation;

use OnePix\WordPressContracts\AdminPage;

function get_plugin_page_hookname(AdminPage $adminPage): string
{
    return \get_plugin_page_hookname($adminPage->getMenuSlug(), $adminPage->getParentSlug());
}