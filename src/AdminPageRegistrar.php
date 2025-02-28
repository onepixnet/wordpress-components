<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use Closure;
use OnePix\WordPressContracts\AdminPage;

final class AdminPageRegistrar implements \OnePix\WordPressContracts\AdminPageRegistrar
{
    private Closure $printContentAutowire;

    /**
     * @param null|Closure(callable):void $printContentAutowire function from di container to autowire dependencies in printContent function.
     */
    public function __construct(
        private readonly \OnePix\WordPressContracts\ActionsRegistrar $actionsRegistrar,
        null|Closure $printContentAutowire = null,
    )
    {
        if (!$printContentAutowire instanceof \Closure) {
            $this->printContentAutowire = static function (Closure $callback): void {
                call_user_func($callback);
            };
        }
    }

    public function addPage(AdminPage $adminPage): void
    {
        $adminPage->getParentSlug() === null ?
            add_menu_page(
                $adminPage->getPageTitle(),
                $adminPage->getMenuTitle(),
                $adminPage->getCapability(),
                $adminPage->getMenuSlug(),
                function () use($adminPage): void{
                    call_user_func($this->printContentAutowire, $adminPage->printContent(...));
                },
                $adminPage->getIconUrl() ?? '',
                $adminPage->getPosition()
            ) :
            add_submenu_page(
                $adminPage->getParentSlug(),
                $adminPage->getPageTitle(),
                $adminPage->getMenuTitle(),
                $adminPage->getCapability(),
                $adminPage->getMenuSlug(),
                function () use($adminPage): void{
                    call_user_func($this->printContentAutowire, $adminPage->printContent(...));
                },
                $adminPage->getPosition()
            );

        $this->actionsRegistrar->add('admin_print_scripts-' . $adminPage->getPageHookName(), $adminPage->enqueueScripts(...), acceptedArgs: 0);
        $this->actionsRegistrar->add('admin_print_styles-' . $adminPage->getPageHookName(), $adminPage->enqueueStyles(...), acceptedArgs: 0);
    }
}
