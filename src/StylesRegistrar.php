<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use OnePix\WordPressContracts\Style;

final class StylesRegistrar implements \OnePix\WordPressContracts\StylesRegistrar
{
    public function registerStyle(Style $style): bool
    {
        return wp_register_style(
            $style->getHandle(),
            $style->getSrc(),
            $style->getDeps(),
            $style->getVersion(),
            $style->getMedia()
        );
    }

    public function enqueueStyle(Style $style): bool
    {
        wp_enqueue_style($style->getHandle());
        return true;
    }

    public function deregisterStyle(Style $style): bool
    {
        wp_deregister_style($style->getHandle());
        return true;
    }

    public function dequeueStyle(Style $style): bool
    {
        wp_dequeue_style($style->getHandle());
        return true;
    }
}
