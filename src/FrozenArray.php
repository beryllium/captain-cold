<?php

namespace Whateverthing\CaptainCold;

/**
 * The cold never bothered me anyway.
 *
 * @package     Captain Cold
 * @copyright   Copyright © 2023 Kevin Boyd (kevin@whateverthing.com)
 * @license     MIT
 */
class FrozenArray extends FreezableArray
{
    // Set the mode to READONLY from the start;
    // constructor is the only way to populate the array.
    protected string $mode = self::READONLY;

    public function __construct(public readonly array $readonly = []) {}
}