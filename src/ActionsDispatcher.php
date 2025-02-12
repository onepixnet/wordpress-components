<?php

declare(strict_types=1);

namespace OnePix\WordPressContractsImplementation;

final class ActionsDispatcher implements \OnePix\WordPressContracts\ActionsDispatcher
{
    public function do(string $hook, mixed ...$args): void
    {
        do_action($hook, $args);
    }
}
