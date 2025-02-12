<?php

declare(strict_types=1);

namespace OnePix\WordPressContractsImplementation;

final class OptionsManager implements \OnePix\WordPressContracts\OptionsManager
{
    public function addOption(string $option, mixed $value, ?bool $autoload = null): bool
    {
        return add_option($option, $value, '', $autoload ?? false);
    }

    public function getOption(string $option, mixed $defaultValue = null): mixed
    {
        return get_option($option, $defaultValue);
    }

    public function updateOption(string $option, mixed $value, ?bool $autoload = null): bool
    {
        return update_option($option, $value, $autoload ?? false);
    }

    public function deleteOption(string $option): bool
    {
        return delete_option($option);
    }
}
