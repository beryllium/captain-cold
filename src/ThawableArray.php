<?php

namespace Whateverthing\CaptainCold;

/**
 * some winters
 * will never melt
 *
 * some summers
 * will never freeze
 *
 * and some things will only
 * ... live in poems.
 *
 * -- Sanober Khan, Turquoise Silence
 *
 * @package     Captain Cold
 * @copyright   Copyright © 2023 Kevin Boyd (kevin@whateverthing.com)
 * @license     MIT
 */
class ThawableArray extends FreezableArray
{
    /**
     * A frozen array must always stay frozen/immutable.
     *
     * Thaw, then, does not unfreeze the array - it creates
     * and returns a new ThawableArray object.
     *
     * However, if the array has not yet been frozen,
     * a Thaw operation will return the object itself.
     *
     * @todo Does this actually make sense? Or should it always
     *       return a fresh object? If the original variable is
     *       then frozen, both variables would be frozen...
     *       (see testThawedThawNoop for an example of what I mean)
     *
     * @return $this
     */
    public function thaw(): ThawableArray {
        if (self::READWRITE === $this->mode) {
            return $this;
        }

        return new static($this->readonly);
    }
}
