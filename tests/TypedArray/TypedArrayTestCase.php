<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents\TypedArray;

use PHPUnit\Framework\TestCase;
use stdClass;

enum TestColor: string {
    case Red = 'red';
    case Green = 'green';
    case Blue = 'blue';
}

/**
 * @covers TypedArray
 */
abstract class TypedArrayTestCase extends TestCase
{
    abstract protected function getTypedArray(array $array): \OnePix\WordPressComponents\TypedArray\Contracts\TypedArray;

    public function testGetIntWithValidIntValue()
    {
        $data = $this->getTypedArray([
            'key1' => 123,
        ]);

        $this->assertEquals(123, $data->getInt('key1', 0));
    }

    public function testGetIntWithValidStringValue()
    {
        $data = $this->getTypedArray([
            'key1' => '456',
        ]);

        $this->assertEquals(456, $data->getInt('key1', 0));
    }

    public function testGetIntWithFloatValue()
    {
        $data = $this->getTypedArray([
            'key1' => 213.321,
        ]);

        $this->assertEquals(0, $data->getInt('key1', 0));
    }

    public function testGetIntWithFloatInStringValue()
    {
        $data = $this->getTypedArray([
            'key1' => '213.321',
        ]);

        $this->assertEquals(0, $data->getInt('key1', 0));
    }

    public function testGetIntWithNonNumericStringValue()
    {
        $data = $this->getTypedArray([
            'key1' => 'abc',
        ]);

        $this->assertEquals(0, $data->getInt('key1', 0));
    }

    public function testGetIntWithMissingKey()
    {
        $data = $this->getTypedArray([
            'key1' => 123,
        ]);

        $this->assertEquals(0, $data->getInt('key2', 0));
    }

    public function testGetIntWithFallbackValue()
    {
        $data = $this->getTypedArray([
            'key1' => '123',
        ]);

        $this->assertEquals(999, $data->getInt('key2', 999));
    }

    public function testGetIntWithInvalidNumericString()
    {
        $data = $this->getTypedArray([
            'key1' => '123abc',
        ]);

        $this->assertEquals(0, $data->getInt('key1', 0));
    }

    public function testGetNumberWithValidFloatValue()
    {
        $data = new TypedArray([
            'key1' => 123.45,
        ]);

        $this->assertEquals(123.45, $data->getNumber('key1', 0.0));
    }

    public function testGetNumberWithValidStringValue()
    {
        $data = new TypedArray([
            'key1' => '456.78',
        ]);

        $this->assertEquals(456.78, $data->getNumber('key1', 0.0));
    }

    public function testGetNumberWithIntegerValue()
    {
        $data = new TypedArray([
            'key1' => 789,
        ]);

        $this->assertEquals(789.0, $data->getNumber('key1', 0.0));
    }

    public function testGetNumberWithNonNumericString()
    {
        $data = new TypedArray([
            'key1' => 'abc',
        ]);

        $this->assertEquals(0.0, $data->getNumber('key1', 0.0));
    }

    public function testGetNumberWithMissingKey()
    {
        $data = new TypedArray([
            'key1' => 123.45,
        ]);

        $this->assertEquals(0.0, $data->getNumber('key2', 0.0));
    }

    public function testGetNumberWithFallbackValue()
    {
        $data = new TypedArray([
            'key1' => '100.99',
        ]);

        $this->assertEquals(999.99, $data->getNumber('key2', 999.99));
    }

    public function testGetNumberWithInvalidNumericString()
    {
        $data = new TypedArray([
            'key1' => '123abc',
        ]);

        $this->assertEquals(0.0, $data->getNumber('key1', 0.0));
    }

    public function testGetNumberWithValidNegativeFloat()
    {
        $data = new TypedArray([
            'key1' => -500.75,
        ]);

        $this->assertEquals(-500.75, $data->getNumber('key1', 0.0));
    }

    public function testGetStringWithValidStringValue()
    {
        $data = new TypedArray([
            'key1' => 'Hello, world!',
        ]);

        $this->assertEquals('Hello, world!', $data->getString('key1', 'Default value'));
    }

    public function testGetStringWithNonStringValue()
    {
        $data = new TypedArray([
            'key1' => 123,
            'key2' => 456.78,
            'key3' => null,
            'key4' => new stdClass()
        ]);

        $this->assertEquals('Default value', $data->getString('key1', 'Default value'));
        $this->assertEquals('Default value', $data->getString('key2', 'Default value'));
        $this->assertEquals('Default value', $data->getString('key3', 'Default value'));
        $this->assertEquals('Default value', $data->getString('key4', 'Default value'));
    }

    public function testGetStringWithMissingKey()
    {
        $data = new TypedArray([
            'key1' => 'Hello',
        ]);

        $this->assertEquals('Default value', $data->getString('key2', 'Default value'));
    }

    public function testGetArrayWithValidArrayValue()
    {
        $data = new TypedArray([
            'key1' => ['a', 'b', 'c'],
        ]);

        $this->assertEquals(['a', 'b', 'c'], $data->getArray('key1', ['default']));
    }

    public function testGetArrayWithNonArrayValue()
    {
        $data = new TypedArray([
            'key1' => 123,
        ]);

        $this->assertEquals(['default'], $data->getArray('key1', ['default']));
    }

    public function testGetArrayWithMissingKey()
    {
        $data = new TypedArray([
            'key1' => ['a', 'b', 'c'],
        ]);

        $this->assertEquals(['default'], $data->getArray('key2', ['default']));
    }

    public function testGetArrayWithEmptyArray()
    {
        $data = new TypedArray([
            'key1' => [],
        ]);

        $this->assertEquals([], $data->getArray('key1', ['default']));
    }

    public function testGetArrayWithFallbackValue()
    {
        $data = new TypedArray([
            'key1' => ['a', 'b'],
        ]);

        $this->assertEquals(['default'], $data->getArray('key2', ['default']));
    }

    public function testGetBoolWithTrueString()
    {
        $data = new TypedArray([
            'key1' => 'true',
        ]);

        $this->assertTrue($data->getBool('key1', false));
    }

    public function testGetBoolWithFalseString()
    {
        $data = new TypedArray([
            'key1' => 'false',
        ]);

        $this->assertFalse($data->getBool('key1', true));
    }

    public function testGetBoolWithTrueInteger()
    {
        $data = new TypedArray([
            'key1' => 1,
        ]);

        $this->assertTrue($data->getBool('key1', false));
    }

    public function testGetBoolWithFalseInteger()
    {
        $data = new TypedArray([
            'key1' => 0,
        ]);

        $this->assertFalse($data->getBool('key1', true));
    }

    public function testGetBoolWithValidBoolValue()
    {
        $data = new TypedArray([
            'key1' => true,
            'key2' => false,
        ]);

        $this->assertTrue($data->getBool('key1', false));
        $this->assertFalse($data->getBool('key2', true));
    }

    public function testGetBoolWithInvalidString()
    {
        $data = new TypedArray([
            'key1' => 'not-a-bool',
            'key2' => 'not-a-bool',
        ]);

        $this->assertFalse($data->getBool('key1', false));
        $this->assertTrue($data->getBool('key2', true));
    }

    public function testGetBoolWithMissingKey()
    {
        $data = new TypedArray([
            'key1' => true,
        ]);

        $this->assertFalse($data->getBool('key2', false));
    }

    public function testGetBoolWithZeroString()
    {
        $data = new TypedArray([
            'key1' => '0',
        ]);

        $this->assertFalse($data->getBool('key1', true)); // '0' string should be false
    }

    public function testGetBoolWithOneString()
    {
        $data = new TypedArray([
            'key1' => '1',
        ]);

        $this->assertTrue($data->getBool('key1', false)); // '1' string should be true
    }

    public function testGetBoolWithValidValues()
    {
        $data = new TypedArray([
            'key1' => 'true',
            'key2' => 'false',
            'key3' => '1',
            'key4' => '0',
            'key5' => 1,
            'key6' => 0,
            'key7' => true,
            'key8' => false,
        ]);

        $this->assertTrue($data->getBool('key1', false)); // 'true' -> true
        $this->assertFalse($data->getBool('key2', true)); // 'false' -> false
        $this->assertTrue($data->getBool('key3', false)); // '1' -> true
        $this->assertFalse($data->getBool('key4', true)); // '0' -> false

        $this->assertTrue($data->getBool('key5', false)); // 1 -> true
        $this->assertFalse($data->getBool('key6', true)); // 0 -> false

        $this->assertTrue($data->getBool('key7', false)); // true -> true
        $this->assertFalse($data->getBool('key8', true)); // false -> false
    }

    public function testGetBoolWithInvalidOrMissingValues()
    {
        $data = new TypedArray([
            'key1' => 'not-a-bool',
            'key2' => new stdClass(),
        ]);

        $this->assertFalse($data->getBool('key1', false)); // invalid value -> false
        $this->assertFalse($data->getBool('key2', false)); // invalid value -> false
        $this->assertFalse($data->getBool('key3', false)); // no value -> false
    }

    public function testGetEnumWithValidEnumValue()
    {
        $data = new TypedArray([
            'key1' => 'red',
        ]);

        $result = $data->getEnum('key1', TestColor::class);
        $this->assertInstanceOf(TestColor::class, $result);
        $this->assertEquals(TestColor::Red, $result);
    }

    public function testGetEnumWithInvalidEnumValue()
    {
        $data = new TypedArray([
            'key1' => 'no color',
            'key2' => new stdClass(),
        ]);

        $this->assertNull($data->getEnum('key1', TestColor::class));
        $this->assertNull($data->getEnum('key2', TestColor::class));
    }

    public function testGetEnumWithMissingKey()
    {
        $data = new TypedArray([
            'key1' => 'green',
        ]);

        $this->assertNull($data->getEnum('key2', TestColor::class));
    }

    public function testGetEnumWithFallback()
    {
        $data = new TypedArray([
            'key1' => 'yellow',
        ]);

        $this->assertEquals(
            TestColor::Red,
            $data->getEnum('key1', TestColor::class, TestColor::Red)
        );
    }

    public function testGetEnumWithValidEnumAndFallback()
    {
        $data = new TypedArray([
            'key1' => 'blue',
        ]);

        $this->assertEquals(
            TestColor::Blue,
            $data->getEnum('key1', TestColor::class, TestColor::Green)
        );
    }
}
