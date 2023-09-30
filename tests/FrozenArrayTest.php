<?php
declare(strict_types=1);

use Whateverthing\CaptainCold\FrozenArray;
use PHPUnit\Framework\TestCase;

class FrozenArrayTest extends TestCase
{
    public function testFrozenGet() {
        $array = new FrozenArray(['one', 'two']);

        self::assertSame('one', $array[0]);
        self::assertSame('two', $array[1]);
    }

    public function testFrozenExists() {
        $array = new FrozenArray(['one', 'two']);

        self::assertTrue(isset($array[0]));
    }

    public function testFrozenEmptySet() {
        try {
            $array = new FrozenArray();

            $array[1] = 'test';
        } catch (Error $err) {
            $this->assertStringContainsString('Cannot modify readonly property', $err->getMessage());
            return;
        }

        $this->fail('Expected a fatal error!');
    }

    public function testFrozenFullSet() {
        try {
            $array = new FrozenArray(['one', 'two']);

            self::assertSame('two', $array[1]);

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
            $array = new FrozenArray(['one', 'two']);

            unset($array[1]);
        } catch (Error $err) {
            $this->assertStringContainsString('Cannot modify readonly property', $err->getMessage());
            return;
        }

        $this->fail('Expected a fatal error!');
    }
}