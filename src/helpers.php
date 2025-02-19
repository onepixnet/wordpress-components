<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use Closure;
use OnePix\WordPressContracts\AdminPage;
use ReflectionFunction;
use ReflectionNamedType;

function get_admin_page_hook_name(AdminPage $adminPage): string
{
    return get_plugin_page_hookname($adminPage->getMenuSlug(), $adminPage->getParentSlug() ?? '');
}

/**
 * @param  Closure(mixed):mixed|callable-string  $callback
 */
function hasFunctionReturnValue(Closure|string $callback): bool
{
    try {
        $reflection = new ReflectionFunction($callback);
    } catch (\Exception) {
        return false;
    }

    $returnType = $reflection->getReturnType();

    //If the return type is not specified, we decide that nothing is returned.
    if ($returnType === null) {
        return false;
    }

    //Only simple types can be void or never
    if (! $returnType instanceof ReflectionNamedType) {
        return true;
    }

    return $returnType->getName() !== 'void' && $returnType->getName() !== 'never';
}
