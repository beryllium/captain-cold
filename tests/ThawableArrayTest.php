<?php
declare(strict_types=1);

use Whateverthing\CaptainCold\ThawableArray;
use PHPUnit\Framework\TestCase;

class ThawableArrayTest extends TestCase
{
    public function testUnfrozenGet() {
        $array = new ThawableArray(['one', 'two']);

        self::assertSame('one', $array[0]);
        self::assertSame('two', $array[1]);
    }

    public function testUnfrozenExists() {
        $array = new ThawableArray(['one', 'two']);

        self::assertTrue(isset($array[0]));
    }

    public function testUnfrozenSet() {
        $array = new ThawableArray();

        $array[] = 'test';

        self::assertSame('test', $array[0]);
    }

    public function testUnfrozenUnset() {
        $array = new ThawableArray(['one', 'two']);

        unset($array[1]);

        self::assertEquals(new ThawableArray(['one']), $array);
    }

    public function testFrozenGet() {
        $array = new ThawableArray(['one', 'two']);
        $array->freeze();

        self::assertSame('one', $array[0]);
        self::assertSame('two', $array[1]);
    }

    public function testFrozenExists() {
        $array = new ThawableArray(['one', 'two']);
        $array->freeze();

        self::assertTrue(isset($array[0]));
    }

    public function testFrozenSet() {
        try {
            $array = new ThawableArray();
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
            $array = new ThawableArray(['one', 'two']);
            $array->freeze();

            unset($array[1]);
        } catch (Error $err) {
            $this->assertStringContainsString('Cannot modify readonly property', $err->getMessage());
            return;
        }

        $this->fail('Expected a fatal error!');
    }

    public function testThawedGet() {
        $array = new ThawableArray(['one', 'two']);
        $array->freeze();

        $thawed = $array->thaw();

        self::assertNotSame($thawed, $array);

        self::assertSame('one', $thawed[0]);
        self::assertSame('two', $thawed[1]);
    }

    public function testThawedExists() {
        $array = new ThawableArray(['one', 'two']);
        $array->freeze();

        $thawed = $array->thaw();

        self::assertNotSame($thawed, $array);
        self::assertTrue(isset($thawed[0]));
    }

    public function testThawedSet() {
        $array = new ThawableArray();
        $array->freeze();

        $thawed = $array->thaw();

        $thawed[] = 'test';

        self::assertSame('test', $thawed[0]);

        try {
            $array[] = 'testing';
        } catch (Error $err) {
            $this->assertStringContainsString('Cannot modify readonly property', $err->getMessage());
            return;
        }

        $this->fail('Original array should still be frozen');
    }

    public function testThawedUnset() {
        $array = new ThawableArray(['one', 'two']);
        $array->freeze();

        $thawed = $array->thaw();

        unset($thawed[1]);

        self::assertEquals(new ThawableArray(['one']), $thawed);

        try {
            unset($array[1]);
        } catch (Error $err) {
            $this->assertStringContainsString('Cannot modify readonly property', $err->getMessage());
            return;
        }

        $this->fail('Original array should still be frozen');
    }

    /**
     * Enforces behaviour as intended ... however, there is some
     * oddness around how $array->freeze() would cause $thawed to
     * also become frozen. Could be confusing in some code.
     *
     * @return void
     */
    public function testThawedThawNoop() {
        $array = new ThawableArray(['one', 'two']);
        $thawed = $array->thaw();

        $this->assertSame($array, $thawed);

        $array->freeze();
        $thawed = $array->thaw();

        $this->assertNotSame($array, $thawed);
    }
}