<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

final class FiltersRegistrar implements \OnePix\WordPressContracts\FiltersRegistrar
{
    public function add(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        /**
         * ToDo fix this error
         *
         * @psalm-suppress MixedArgumentTypeCoercion
         * @psalm-suppress ArgumentTypeCoercion
         * @psalm-suppress PossiblyInvalidArgument
         */
        add_filter($hook, $callback, $priority, $acceptedArgs);
    }

    public function remove(string $hook, callable $callback, int $priority = 10): void
    {
        remove_filter($hook, $callback, $priority);
    }

    public function has(string $hook, callable $callback): bool
    {
        return has_filter($hook, $callback);
    }

    public function hasAny(string $hook): bool
    {
        return has_filter($hook);
    }
}
