<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents\TypedArray;

use BackedEnum;

final class TypedArray implements Contracts\TypedArray
{
    public function __construct(
        private array $data
    )
    {
    }

    public function getInt(string|int $key, int $fallback): int
    {
        if (
            isset($this->data[$key])
            && (is_int($this->data[$key])
                || (is_string($this->data[$key]) && ctype_digit($this->data[$key]))
            )
        ) {
            return (int) $this->data[$key];
        }

        return $fallback;
    }

    public function getNumber(string|int $key, float $fallback): float
    {
        if (isset($this->data[$key]) && is_numeric($this->data[$key])) {
            return (float) $this->data[$key];
        }

        return $fallback;
    }

    public function getString(string|int $key, string $fallback): string
    {
        if (isset($this->data[$key]) && is_string($this->data[$key])) {
            return $this->data[$key];
        }

        return $fallback;
    }

    public function getArray(string|int $key, array $fallback): array
    {
        if (isset($this->data[$key]) && is_array($this->data[$key])) {
            return $this->data[$key];
        }

        return $fallback;
    }

    /**
     * 'true', '1', 1 => true
     * 'false', '0', 0 => false
     * default => $fallback
     */
    public function getBool(string|int $key, bool $fallback): bool
    {
        if (! isset($this->data[$key])) {
            return $fallback;
        }

        $value = $this->data[$key];

        if (is_bool($value)) {
            return $value;
        }

        if ($value === 1 || $value === 0) {
            return $value === 1;
        }

        if (is_string($value)) {
            $lowerValue = strtolower($value);
            if ($lowerValue === 'true' || $value === '1') {
                return true;
            }

            if ($lowerValue === 'false' || $value === '0') {
                return false;
            }
        }

        return $fallback;
    }

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
    public function getEnum(string|int $key, string $enumClass, ?BackedEnum $fallback = null): ?BackedEnum
    {
        if (isset($this->data[$key]) && is_string($this->data[$key])) {
            /** @var T|null $enumInstance */
            $enumInstance = $enumClass::tryFrom($this->data[$key]);
            if ($enumInstance !== null) {
                return $enumInstance;
            }
        }

        return $fallback;
    }
}
