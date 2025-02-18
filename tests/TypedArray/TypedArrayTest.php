<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents\TypedArray;

/**
 * @covers TypedArray
 */
class TypedArrayTest extends TypedArrayTestCase
{
    protected function getTypedArray(array $array): TypedArray
    {
        return new TypedArray($array);
    }
}
