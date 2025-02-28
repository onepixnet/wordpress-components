<?php

declare(strict_types=1);

use Illuminate\Contracts\Container\Container;

/**
 * @see https://laravel.com/docs/11.x/container
 */
return static function (Container $container): Container {
    $container = clone $container;

    /**
     * Bind classes with container.
     *
     * @see Container::bind()
     * @see Container::singleton()
     *
     * $container->bind(SomeInterface::class, SomeClassImplementingInterface::class);
     */

    $container->bind(OnePix\WordPressComponents\Ajax\AjaxManager::class);//Needs $runAction (optional)

    $container->bind(OnePix\WordPressContracts\ActionsDispatcher::class, OnePix\WordPressComponents\ActionsDispatcher::class);
    $container->bind(OnePix\WordPressContracts\ActionsRegistrar::class, OnePix\WordPressComponents\ActionsRegistrar::class);
    $container->bind(OnePix\WordPressContracts\AdminPageRegistrar::class, OnePix\WordPressComponents\AdminPageRegistrar::class); //Needs $printContentAutowire (optional)
    $container->bind(OnePix\WordPressContracts\FiltersDispatcher::class, OnePix\WordPressComponents\FiltersDispatcher::class);
    $container->bind(OnePix\WordPressContracts\FiltersRegistrar::class, OnePix\WordPressComponents\FiltersRegistrar::class);
    $container->bind(OnePix\WordPressContracts\HooksManager::class, OnePix\WordPressComponents\HooksManager::class); //Needs $hooks
    $container->bind(OnePix\WordPressContracts\OptionsManager::class, OnePix\WordPressComponents\OptionsManager::class);
    $container->bind(OnePix\WordPressContracts\PluginLifecycleHandler::class, OnePix\WordPressComponents\PluginLifecycleHandler::class); //Needs $pluginFile
    $container->bind(OnePix\WordPressContracts\RewriteRulesManager::class, OnePix\WordPressComponents\RewriteRulesManager::class); //Needs $optionPrefix.
    $container->bind(OnePix\WordPressContracts\ScriptsRegistrar::class, OnePix\WordPressComponents\ScriptsRegistrar::class); //Needs $translationDomain and $translationsPath.
    $container->bind(OnePix\WordPressContracts\StylesRegistrar::class, OnePix\WordPressComponents\StylesRegistrar::class);
    $container->bind(OnePix\WordPressContracts\TemplatesManager::class, OnePix\WordPressComponents\TemplatesManager::class); //Needs $templatesPath and $isDev.

    $container->singleton(\OnePix\WordPressComponents\DbTableCreator::class, function() {
        global $wpdb;

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }

        return new \OnePix\WordPressComponents\DbTableCreator($wpdb->get_charset_collate(), dbDelta(...));
    });

    return $container;
};