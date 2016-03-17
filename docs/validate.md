# Rules To Validate Fields

## alnum

Validates the value as alphanumeric only.

```php
$filter->validate('field')->is('alnum');
```

## alpha

Validates the value as alphabetic only.

```php
$filter->validate('field')->is('alpha');
```

## between

Validates the value as being within or equal to a minimum and maximum value.

```php
$filter->validate('field')->is('between', $min, $max);
```

## blank

Validates the value as being blank.

```php
$filter->validate('field1')->isBlank();
$filter->validate('field2')->isBlankOr('strlen', 3);
$filter->validate('field3')->isBlankOrNot('strlen', 3);
```

To validate the value as not being blank.

```php
$filter->validate('field')->isNotBlank();
```

## bool

Validates the value as being a boolean, or a pseudo-boolean. Pseudo-true values include the strings '1', 'y', 'yes', and 'true'; pseudo-false values include the strings '0', 'n', 'no', and 'false'.

```php
$filter->validate('field')->is('bool');
```

## callback

Validates the value using a callable/callback. The callable should take two arguments, `$subject` and `$field`, to indicate the subject and the field within that subject. It should return `true` to pass, or `false` to fail.

```php
$filter->validate('field')->is('callback', function ($subject, $field) {
    if ($subject->$field === 'foo') {
        return true;
    }
    return false;
});
```

> N.b.: Always use object notation (`$subject->$field`) and not array notation (`$subject[$field]`) in the closure, as the _Filter_ converts arrays to objects on the fly.

## creditCard

Validates the value as being a credit card number.

```php
$filter->validate('field')->is('creditCard');
```

## dateTime

Validates the value as representing a date and/or time.

```php
$filter->validate('field')->is('dateTime');
```

## email

Validates the value as being a properly-formed email address per the various relevant RFCs. If the `intl` extension is loaded, it will also allow for international domain names.

```php
$filter->validate('field')->is('email');
```

## equalToField

Validates the value as loosely equal (`==`) to the value of another
field in the subject.

```php
$filter->validate('field')->is('equalToField', 'other_field_name');
```

## equalToValue

Validates the value as loosely equal (`==') to a specified value.

```php
$filter->validate('field')->is('equalToValue', $other_value);
```

## float

Validates the value as representing a float.

```php
$filter->validate('field')->is('float');
```

## inKeys

Validates that the value is loosely equal (`==`) to a key in a given array.

```php
$filter->validate('field')->is('inKeys', $array);
```

## int

Validates the value as representing an integer.

```php
$filter->validate('field')->is('int');
```

## inValues

Validates that the value is strictly equal (`===`) to a value in a given array.

```php
$filter->validate('field')->is('inValues', $array);
```

## ip

Validates the value as an IPv4 or IPv6 address, allowing reserved and private addresses.

```php
$filter->validate('field')->is('ip');
```

To modify restrictions on the filter, pass the appropriate `FILTER_FLAG_*` constants (seen [here](http://php.net/manual/en/filter.filters.flags.php)) as a second parameter.

```php
// only allow IPv4 addresses in the non-private range.
$filter->validate('field')->is('ip', FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE);

// only allow IPv6 addresses in non-reserved range.
$filter->validate('field')->is('ip', FILTER_FLAG_IPV6 | FILTER_FLAG_NO_RES_RANGE);
```

## isbn

Validates the value is a correct ISBN (International Standard Book Number).

```php
$filter->validate('field')->is('isbn');
```

## locale

Validates the given value against a list of locale strings (internal to the rule class).

```php
$filter->validate('field')->is('locale');
```

## lowercase

Validates the value as all lowercase.

```php
$filter->validate('field')->is('lowercase');
```

## lowercaseFirst

Validates the value begins with a lowercase character.

```php
$filter->validate('field')->is('lowercaseFirst');
```

## max

Validates the value as being less than or equal to a maximum.

```php
$filter->validate('field')->is('max', $max);
```

## min

Validates the value as being greater than or equal to a minimum.

```php
$filter->validate('field')->is('min', $min);
```

## regex

Validates the value using `preg_match()`.

```php
$filter->validate('field')->is('regex', $expr);
```

## strictEqualToField

Validates the value as strictly equal (`===`) to the value of another field in the subject.

```php
$filter->validate('field')->is('strictEqualToField', 'other_field_name');
```

## strictEqualToValue

Validates the value as strictly equal (`===`) to a specified value.

```php
$filter->validate('field')->is('strictEqualToValue', $other_value);
```

## string

Validates the value can be represented by a string.

```php
$filter->validate('field')->is('string');
```

## strlen

Validates the value has a specified length.

```php
$filter->validate('field')->is('strlen', $len);
```

## strlenBetween

Validates the value as being within or equal to a minimum and maximum length.

```php
$filter->validate('field')->is('strlenBetween', $min, $max);
```

## strlenMax

Validates the value length as being no longer than a maximum.

```php
$filter->validate('field')->is('strlenMax', $max);
```

## strlenMin

Validates the value length as being no shorter than a minimum.

```php
$filter->validate('field')->is('strlenMin', $min);
```

## titlecase

Validates the value as title case

```php
$filter->validate('field')->is('titlecase');
```

## trim

Validates the value is `trim()`med. Optionally specify characters to trim.

```php
$filter->validate('field')->is('trim', $chars);
```

## upload

Validates the value represents a PHP upload information array, and that the file is an uploaded file.

```php
$filter->validate('field')->is('upload');
```

## uppercase

Validates the value as all uppercase.

```php
$filter->validate('field')->is('uppercase');
```

## uppercaseFirst

Validates the value begins with an uppercase character.

```php
$filter->validate('field')->is('uppercaseFirst');
```

## url

Validates the value is a well-formed URL.

```php
$filter->validate('field')->is('url');
```

## word

Validates the value as being composed only of word characters.

```php
$filter->validate('field')->is('word');
```
