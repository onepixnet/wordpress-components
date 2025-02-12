<?php

declare(strict_types=1);

namespace OnePix\WordPressContractsImplementation;

use OnePix\WordPressContracts\Script;

final class ScriptsRegistrar implements \OnePix\WordPressContracts\ScriptsRegistrar
{
    public function __construct(
        private readonly string $translationDomain = 'default',
        private readonly string $translationsPath = ''
    )
    {
    }

    public function registerScript(Script $script): bool
    {
        wp_register_script(
            $script->getHandle(),
            $script->getSrc(),
            $script->getDeps(),
            $script->getVersion(),
            $script->getArgs()
        );

        foreach ($script->getData() as $objectName => $data) {
            wp_localize_script(
                $script->getHandle(),
                $objectName,
                $data
            );
        }

        if ($script->isTranslatable()) {
            wp_set_script_translations(
                $script->getHandle(),
                $this->translationDomain,
                $this->translationsPath
            );
        }

        return true;
    }

    public function enqueueScript(Script $script): bool
    {
        wp_enqueue_script($script->getHandle());
        return true;
    }

    public function deregisterScript(Script $script): bool
    {
        wp_deregister_script($script->getHandle());
        return true;
    }

    public function dequeueScript(Script $script): bool
    {
        wp_dequeue_script($script->getHandle());
        return true;
    }
}
