<?php

namespace Whateverthing\CaptainCold;

/**
 * Winter is coming.
 *
 * @package     Captain Cold
 * @copyright   Copyright © 2023 Kevin Boyd (kevin@whateverthing.com)
 * @license     MIT
 */
class FreezableArray implements \ArrayAccess
{
    protected const READONLY = 'readonly';
    protected const READWRITE = 'readwrite';

    protected string $mode = self::READWRITE;
    protected readonly array $readonly;

    public function __construct(protected array $readwrite = []) {}

    public function freeze() {
        $this->readonly = $this->readwrite;
        $this->mode = self::READONLY;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->{$this->mode}[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$this->mode}[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->{$this->mode}[] = $value;

            return;
        }

        $this->{$this->mode}[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$this->mode}[$offset]);
    }
}