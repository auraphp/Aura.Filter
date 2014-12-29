# Rules To Validate Fields

## alnum

Validates the value as alphanumeric only.

```php
<?php
$filter->validate('field')->is('alnum');
?>
```

## alpha

Validates the value as alphabetic only.

```php
<?php
$filter->validate('field')->is('alpha');
?>
```

## between

Validates the value as being within or equal to a minimum and
maximum value.

```php
<?php
$filter->validate('field')->is('between', $min, $max);
?>
```

## blank

Validates the value as being blank.

```php
<?php
$filter->validate('field')->is('blank');
?>
```

## bool

Validates the value as being a boolean, or a pseudo-boolean.
Pseudo-true values include the strings '1', 'y', 'yes', and 'true';
pseudo-false values include the strings '0', 'n', 'no', and 'false'.

```php
<?php
$filter->validate('field')->is('bool');
?>
```

## closure

Validates the value using a closure. The closure should take two arguments,
`$subject` and `$field` to indicate the subject (either an array or object)
and the field within that subject. It should return `true` to pass, or `false`
to fail.

```php
<?php
$filter->validate('field')->is('closure', function ($subject, $field) {
    if ($subject->$field === 'foo') {
        return true;
    }
    return false;
});
?>
```

## creditCard

Validates the value as being a credit card number.

```php
<?php
$filter->validate('field')->is('creditCard');
?>
```

## dateTime

Validates the value as representing a date and/or time.

```php
<?php
$filter->validate('field')->is('dateTime');
?>
```

## email

Validates the value as being a properly-formed email address.

```php
<?php
$filter->validate('field')->is('email');
?>
```

## equalToField

Validates the value as loosely equal (`==`) to the value of another
field in the subject.

```php
<?php
$filter->validate('field')->is('equalToField', 'other_field_name');
?>
```

## equalToValue

Validates the value as loosely equal (`==') to a specified value.

```php
<?php
$filter->validate('field')->is('equalToValue', $other_value);
?>
```

## float

Validates the value as representing a float.

```php
<?php
$filter->validate('field')->is('float');
?>
```

## inKeys

Validates that the value is loosely equal (`==`) to a key in a given array.

```php
<?php
$filter->validate('field')->is('inKeys', $array);
?>
```

## int

Validates the value as representing an integer.

```php
<?php
$filter->validate('field')->is('int');
?>
```

## inValues

Validates that the value is strictly equal (`===`) to a value in a given array.

```php
<?php
$filter->validate('field')->is('inValues', $array);
?>
```

## ipv4

Validates the value as an IPv4 address.

```php
<?php
$filter->validate('field')->is('ipv4');
?>
```

## isbn

Validates the value is a correct ISBN (International Standard Book Number).

```php
<?php
$filter->validate('field')->is('isbn');
?>
```

## locale

Validates the given value against a list of locale strings (internal to the
rule class).

```php
<?php
$filter->validate('field')->is('locale');
?>
```

## max

Validates the value as being less than or equal to a maximum.

```php
<?php
$filter->validate('field')->is('max', $max);
?>
```

## min

Validates the value as being greater than or equal to a minimum.

```php
<?php
$filter->validate('field')->is('min', $min);
?>
```

## regex

Validates the value using `preg_match()`.

```php
<?php
$filter->validate('field')->is('regex', $expr);
?>
```

## strictEqualToField

Validates the value as strictly equal (`===`) to the value of another field in
the subject.

```php
<?php
$filter->validate('field')->is('strictEqualToField', 'other_field_name');
?>
```

## strictEqualToValue

Validates the value as strictly equal (`===`) to a specified value.

```php
<?php
$filter->validate('field')->is('strictEqualToValue', $other_value);
?>
```

## string

Validates the value can be represented by a string.
```php
<?php
$filter->validate('field')->is('string');
?>
```

## strlen

Validates the value has a specified length.

```php
<?php
$filter->validate('field')->is('strlen', $len);
?>
```

## strlenBetween

Validates the value as being within or equal to a minimum and maximum length.

```php
<?php
$filter->validate('field')->is('strlenBetween', $min, $max);
?>
```

## strlenMax

Validates the value length as being no longer than a maximum.

```php
<?php
$filter->validate('field')->is('strlenMax', $max);
?>
```

## strlenMin

Validates the value length as being no shorter than a minimum.

```php
<?php
$filter->validate('field')->is('strlenMin', $min);
?>
```

## trim

Validates the value is `trim()`med. Optionally specify characters to trim.

```php
<?php
$filter->validate('field')->is('trim', $chars);
?>
```

## upload

Validates the value represents a PHP upload information array, and that the file
is an uploaded file.

```php
<?php
$filter->validate('field')->is('upload');
?>
```

## url

Validates the value is a well-formed URL.

```php
<?php
$filter->validate('field')->is('url');
?>
```

## word

Validates the value as being composed only of word characters.

```php
<?php
$filter->validate('field')->is('word');
?>
```
