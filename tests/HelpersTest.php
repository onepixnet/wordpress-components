<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use PHPUnit\Framework\TestCase;
use stdClass;

class HelpersTest extends TestCase
{
    public function testHasFunctionReturnValueHasReturnValue(): void
    {
        $callbacks = [
            fn(): int => 42,
            fn(): float => 3.14,
            fn(): string => 'Hello',
            fn(): bool => true,
            fn(): array => [1, 2, 3],
            fn(): object => new stdClass(),
            fn(): ?int => null,
            fn(): callable => fn(): string => 'Hello',
            fn(): int|string => 42,
        ];

        foreach ($callbacks as $callback) {
            $this->assertTrue(hasFunctionReturnValue($callback));
        }
    }

    public function testHasFunctionReturnValueHasNoReturnValue(): void
    {
        $callbacks = [
            static function(): void {
            },
            static function(): never { throw new \Exception('Never return');
            },
            /**
             * @psalm-suppress MissingClosureReturnType
             * @psalm-suppress UndefinedFunction
             */
            static function() {
                echo 'prevent rector';
                return smth();
            },
            '__invalidCallback__',
        ];

        foreach ($callbacks as $callback) {
            /** @psalm-suppress PossiblyInvalidArgument */
            $this->assertFalse(hasFunctionReturnValue($callback));
        }
    }
}
