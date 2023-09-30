<?php
declare(strict_types=1);

use Whateverthing\CaptainCold\FreezableArray;
use PHPUnit\Framework\TestCase;

class FreezableArrayTest extends TestCase
{
    public function testUnfrozenGet() {
        $array = new FreezableArray(['one', 'two']);

        self::assertSame('one', $array[0]);
        self::assertSame('two', $array[1]);
    }

    public function testUnfrozenExists() {
        $array = new FreezableArray(['one', 'two']);

        self::assertTrue(isset($array[0]));
    }

    public function testUnfrozenSet() {
        $array = new FreezableArray();

        $array[] = 'test';

        self::assertSame('test', $array[0]);
    }

    public function testUnfrozenUnset() {
        $array = new FreezableArray(['one', 'two']);

        unset($array[1]);

        self::assertEquals(new FreezableArray(['one']), $array);
    }

    public function testFrozenGet() {
        $array = new FreezableArray(['one', 'two']);
        $array->freeze();

        self::assertSame('one', $array[0]);
        self::assertSame('two', $array[1]);
    }

    public function testFrozenExists() {
        $array = new FreezableArray(['one', 'two']);
        $array->freeze();

        self::assertTrue(isset($array[0]));
    }

    public function testFrozenSet() {
        try {
            $array = new FreezableArray();
            $array->freeze();

            $array[1] = 'test';
        } catch (Error $err) {
            $this->assertStringContainsString('Cannot modify readonly property', $err->getMessage());
            return;
        }

        $this->fail('Expected a fatal error!');
    }

    public function testFrozenUnset()
    {
        try {
            $array = new FreezableArray(['one', 'two']);
            $array->freeze();

            unset($array[1]);
        } catch (Error $err) {
            $this->assertStringContainsString('Cannot modify readonly property', $err->getMessage());
            return;
        }

        $this->fail('Expected a fatal error!');
    }
}