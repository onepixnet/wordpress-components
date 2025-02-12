<?php

declare(strict_types=1);

namespace OnePix\WordPressContractsImplementation;

final class ActionsRegistrar implements \OnePix\WordPressContracts\ActionsRegistrar
{
    public function add(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        /**
         * @psalm-suppress ArgumentTypeCoercion
         * @psalm-suppress PossiblyInvalidArgument
         */
        add_action($hook, $callback, $priority, $acceptedArgs);
    }

    public function remove(string $hook, callable $callback, int $priority = 10): void
    {
        remove_action($hook, $callback, $priority);
    }

    public function has(string $hook, callable $callback): bool
    {
        return has_action($hook, $callback);
    }

    public function hasAny(string $hook): bool
    {
        return has_action($hook);
    }
}
