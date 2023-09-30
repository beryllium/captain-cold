Captain Cold
============

PHP has arrays. PHP has readonly properties. PHP does not have the ability to
take a non-readonly array and flip it to readonly.

This library aims to fix that exceedingly rare edge case.

## Inspiration

I saw a Mastodon post where someone claimed that Lua table expressions are
superior to JSON in every possible way. But when I looked into it, it seemed to
me that Lua table expressions are not so different from PHP tables. They both
seem to mix together the concepts of arrays and dictionaries and so forth.

Except, Lua tables have a `.freeze()` option. PHP doesn't have that.

So I decided to create a silly userland implementation of my own to see if it
might offer some value.

And while my first instinct was to name it after Mister Freeze, and my second
idea was to name it after Nora Fries (aka Mrs Freeze), I decided to name it
after Captain Cold instead. Yet another ice-themed DC villain.

If it turns out to provide some value to someone, I might rename the project to
`Whateverthing\Freezable`.

## Installation

```
composer require whateverthing/captain-cold
```

## Usage

Passing an array into the `FreezableArray` constructor will create an
array-accessible object that you can freeze at your leisure.

### FreezableArray

Example:

```php
$array = ['one', 'two', 'three'];
$freezableArray = new \Whateverthing\CaptainCold\FreezableArray($array);
```

You can then call `->freeze()` to lock the array down:

```php
$freezableArray->freeze();
$freezableArray[] = 'four'; // Fatal error: Cannot modify readonly property
```

### FrozenArray

A `FrozenArray` object is, and will always be, immutable from the moment it is constructed.

```php
$array = ['one', 'two', 'three'];
$freezableArray = new \Whateverthing\CaptainCold\FrozenArray($array);

$freezableArray[] = 'four'; // Fatal error: Cannot modify readonly property
```

### ThawableArray

The `ThawableArray` class deals with immutability the same way as other
immutable objects do: by getting a fresh object.

```php
$array = ['one', 'two', 'three'];
$thawableArray = new \Whateverthing\CaptainCold\ThawableArray($array);
$thawableArray->freeze();

$thawableArray[] = 'four'; // Fatal error: Cannot modify readonly property

$thawedArray = $thawableArray->thaw();

$thawedArray[] = 'four'; // Works!

// However, if you freeze it again, it becomes immutable.
$thawedArray->freeze();
$thawedArray[] = 'five'; // Fatal error: Cannot modify readonly property
```

One quirk of `ThawableArray` is that if the original array has not yet been
frozen, a call to `->thaw()` will return `$this`. This can lead to some odd
behaviour.

```php
$array = ['one', 'two', 'three'];
$thawableArray = new \Whateverthing\CaptainCold\ThawableArray($array);
$thawedArray = $thawableArray->thaw();

$thawedArray[] = 'four'; // Works!

// However, if you freeze it again, it becomes immutable.
$thawedArray->freeze();
$thawedArray[] = 'five'; // Fatal error: Cannot modify readonly property

// But look, the original array has also become frozen!
$thawableArray[] = 'five';  // Fatal error: Cannot modify readonly property
```

Currently, this behaviour is intentional. However, the more I think about it,
the more I feel it is too confusing/dangerous.

## Contributing

Contributions are welcome, but please be aware that I am not the most responsive
person on the planet. If something seems to languish, that probably means I need
to get pinged again in order to discover or rediscover that it exists.

If you have ideas for improvement, let's discuss it in the issues or on
Mastodon, where I can be reached at:

https://phpc.social/@kboyd (@kboyd@phpc.social).

I'm particularly interested in hearing if the idea ever helps anyone.