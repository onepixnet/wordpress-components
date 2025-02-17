<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents\TypedArray\Contracts;

use BackedEnum;

interface TypedArray
{
    public function getInt(string|int $key, int $fallback): int;

    public function getNumber(string|int $key, float $fallback): float;

    public function getString(string|int $key, string $fallback): string;

    public function getArray(string|int $key, array $fallback): array;

    /**
     * 'true', '1', 1 => true
     * 'false', '0', 0 => false
     * default => $fallback
     */
    public function getBool(string|int $key, bool $fallback): bool;

    /**
     * Get an enum instance from the array using BackedEnum::tryFrom
     *
     * @template T of BackedEnum
     *
     * @param  array-key  $key  Array key
     * @param  class-string<T>  $enumClass  The enum class name
     * @param  T|null  $fallback  Fallback value if key doesn't exist or value is not valid for the enum
     *
     * @return T|null
     */
    public function getEnum(string|int $key, string $enumClass, ?BackedEnum $fallback = null): ?BackedEnum;
}
