<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

final class FiltersDispatcher implements \OnePix\WordPressContracts\FiltersDispatcher
{
    public function apply(string $hook, mixed $value, mixed ...$args): mixed
    {
        return apply_filters($hook, $value, $args);
    }
}
