<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use Closure;
use OnePix\WordPressContracts\AdminPage;

final class AdminPageRegistrar implements \OnePix\WordPressContracts\AdminPageRegistrar
{
    private Closure $autowire;

    /**
     * @param null|callable(Closure):void $autowire function from di container to autowire dependencies in printContent function.
     */
    public function __construct(
        private readonly \OnePix\WordPressContracts\ActionsRegistrar $actionsRegistrar,
        ?callable $autowire = null,
    ) {
        if ($autowire === null) {
            $this->autowire = static function (Closure $callback): void {
                call_user_func($callback);
            };
        } else {
            $this->autowire = $autowire(...);
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
                    call_user_func($this->autowire, $adminPage->printContent(...));
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
                    call_user_func($this->autowire, $adminPage->printContent(...));
                },
                $adminPage->getPosition()
            );

        $this->actionsRegistrar->add(
            'admin_print_scripts-' . $adminPage->getPageHookName(),
            function () use($adminPage): void{
                call_user_func($this->autowire, $adminPage->enqueueScripts(...));
            },
            acceptedArgs: 0
        );

        $this->actionsRegistrar->add(
            'admin_print_styles-' . $adminPage->getPageHookName(),
            function () use($adminPage): void{
                call_user_func($this->autowire, $adminPage->enqueueStyles(...));
            },
            acceptedArgs: 0
        );
    }
}
