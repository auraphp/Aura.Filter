# Rules To Sanitize Fields

## alnum

Sanitizes to leave only alphanumeric characters.

```php
$filter->sanitize('field')->to('alnum');
```

## alpha

Sanitizes to leave only alphabetic characters.

```php
$filter->sanitize('field')->to('alpha');
```

## between

Sanitizes so that values lower than the range are forced up to the minimum, and values higher than the range are forced down to the maximum.

```php
$filter->sanitize('field')->to('between', $min, $max);
```

## bool

Sanitizes to a strict PHP boolean value. Pseudo-true values include the strings '1', 'y', 'yes', and 'true'; pseudo-false values include the strings '0', 'n', 'no', and 'false'.

```php
// sanitize to `true` and `false`
$filter->sanitize('field')->to('bool');
```

You can sanitize to alternative true and false values in place of PHP `true` and `false`.

```php
// sanitize to alternative true and false values
$filter->sanitize('field')->to('bool', $value_if_true, $value_if_false);
```

## callback

Sanitizes the value using a callable/callback. The callback should take two arguments, `$subject` and `$field`, to indicate the subject and the field within that subject. It should return `true` to pass, or `false` to fail.

```php
$filter->sanitize('field')->to('callback', function ($subject, $field) {
    // always force the field to 'foo'
    $subject->$field = 'foo';
    return true;
});
```

> N.b.: Always use object notation (`$subject->$field`) and not array notation (`$subject[$field]`) in the callable, as the _Filter_ converts arrays to objects on the fly.

## dateTime

Sanitizes the value to a specified date/time format, default `'Y-m-d H:i:s'`.

```php
$filter->sanitize('field')->to('dateTime', $format);
```

## field

Sanitizes to the value of another field in the subject.

```php
$filter->sanitize('field')->to('field', 'other_field_name');
```

## float

Sanitizes the value to transform it into a float; for weird strings, this may not be what you expect.

```php
$filter->sanitize('field')->to('float');
```

## int

Sanitizes the value to transform it into an integer; for weird strings, this may not be what you expect.

```php
$filter->sanitize('field')->to('int');
```

## isbn

Sanitizes the value to an ISBN (International Standard Book Number).

```php
$filter->sanitize('field')->to('isbn');
```

## lowercase

Sanitizes the value to all lowercase characters.

```
$filter->sanitize('field')->to('lowercase');
```

## lowercaseFirst

Sanitizes the value to begin with a lowercase character.

```
$filter->sanitize('field')->to('lowercaseFirst');
```

## max

Sanitizes so that values higher than the maximum are forced down to the maximum.

```php
$filter->sanitize('field')->to('max', $max);
```

## min

Sanitizes so that values lower than the minimum are forced up to the minimum.

```php
$filter->sanitize('field')->to('min', $min);
```

## now

Sanitizes the value to force it to the current datetime, default format 'Y-m-d H:i:s'.

```php
$filter->sanitize('field')->to('now', $format);
```

## remove

Removes the field from the subject with `unset()`.

```php
$filter->sanitize('field')->to('remove');
```

## regex

Sanitizes the value using `preg_replace()`.

```php
$filter->sanitize('field')->to('regex', $expr);
```

## string

Sanitizes the value by casting to a string and optionally using `str_replace()` to find and replace within the string.

```php
$filter->sanitize('field')->to('string', $find, $replace);
```

## strlen

Sanitizes the value to cut off longer values at the right, and `str_pad()` shorter ones.

```php
$filter->sanitize('field')->to('strlen', $len[, $pad_string[, $pad_type]]);
```

## strlenBetween

Sanitizes the value to truncate values longer than the maximum, and `str_pad()`
shorter ones.

```php
$filter->sanitize('field')->to('strlenBetween', $min, $max[, $pad_string[, $pad_type]]);
```

## strlenMax

Sanitizes the value to truncate values longer than the maximum.

```php
$filter->sanitize('field')->to('strlenMax', $max);
```

## strlenMin

Sanitizes the value to `str_pad()` values shorter than the minimum.

```php
$filter->sanitize('field')->to('strlenMin', $min[, $pad_string[, $pad_type]]);
```

## titlecase

Sanitizes the value to titlecase (eg. "Title Case").

```php
$filter->sanitize('field')->to('titlecase');
```

## trim

Sanitizes the value to `trim()` it. Optionally specify characters to trim.

```php
$filter->sanitize('field')->to('trim', $chars);
```

## uppercase

Sanitizes the value to all uppercase characters.

```
$filter->sanitize('field')->to('uppercase');
```

## uppercaseFirst

Sanitizes the value to begin with an uppercase character.

```
$filter->sanitize('field')->to('uppercaseFirst');
```

## value

Sanitizes to the specified value.

```php
$filter->sanitize('field')->to('value', $other_value);
```

## word

Sanitizes the value to remove non-word characters.

```php
$filter->sanitize('field')->to('word');
```
