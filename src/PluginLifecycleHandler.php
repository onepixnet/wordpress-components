<?php

declare(strict_types=1);

namespace OnePix\WordPressContractsImplementation;

final class PluginLifecycleHandler implements \OnePix\WordPressContracts\PluginLifecycleHandler
{
    public function __construct(private readonly string $pluginFile)
    {
    }

    public function registerActivationHook(callable $callback): void
    {
        register_activation_hook($this->pluginFile, $callback);
    }

    public function registerDeactivationHook(callable $callback): void
    {
        register_deactivation_hook($this->pluginFile, $callback);
    }

    public function registerUninstallHook(callable $callback): void
    {
        register_uninstall_hook($this->pluginFile, $callback);
    }
}
