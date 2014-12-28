# Aura.Filter

This package provides tools to validate and sanitize objects and arrays.

## Foreword

### Installation

This library requires PHP 5.3 or later, and has no userland dependencies.

It is installable and autoloadable via Composer as [aura/filter](https://packagist.org/packages/aura/filter).

Alternatively, [download a release](https://github.com/auraphp/Aura.Filter/releases)
or clone this repository, then require or include its _autoload.php_ file.
You don't need to run composer install in order to run the test suite.

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/auraphp/Aura.Filter/badges/quality-score.png?b=develop-2)](https://scrutinizer-ci.com/g/auraphp/Aura.Filter/)
[![Code Coverage](https://scrutinizer-ci.com/g/auraphp/Aura.Filter/badges/coverage.png?b=develop-2)](https://scrutinizer-ci.com/g/auraphp/Aura.Filter/)
[![Build Status](https://travis-ci.org/auraphp/Aura.Filter.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.Filter)

To run the unit tests at the command line, issue `phpunit -c tests/unit/`.
(This requires [PHPUnit][] to be available as `phpunit`.)

[PHPUnit]: http://phpunit.de/manual/

To run the [Aura.Di][] container configuration tests at the command line, go to
the _tests/container_ directory and issue `./phpunit.sh`. (This requires
[PHPUnit][] to be available as `phpunit` and [Composer][] to be available as
`composer`.)

[Aura.Di]: https://github.com/auraphp/Aura.Di
[Composer]: http://getcomposer.org/

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura
community, please join our [Google Group](http://groups.google.com/group/auraphp),
follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us
on #auraphp on Freenode.


## Getting Started

This document gives a general overview of how to use the library, but we also
have these pages available:

- [Validator rules](README-VALIDATE.md) (rules that validate field values)
- [Sanitizer rules](README-SANITIZE.md) (rules that sanitize field values)
- [Custom rules](README-CUSTOM.md) (how to write custom filter rules)

### Terminology

Unfortunately, there are few common terms shared between filtering/validating/etc.
libraries. To clear up misconception, this library uses the following
definitions:

- "field": an array element or object property

- "validate": determine if a field value conforms to a particular format,
  but do not modify the field value

- "sanitize": modify, transform, or otherwise force a field value to conform
  to a particular format

- "filter": validate and/or sanitize one or more fields

- "subject": the array or object being filtered

### Instantiation

The easiest way to instantiate a new _Filter_ is to use the _FilterFactory_
class:

```php
<?php
use Aura\Filter\FilterFactory;

$filter_factory = new FilterFactory();
$filter = $filter_factory->newInstance();
?>
```

### Adding Filter Specifications

Add filtering specifications to the filter for each subject field.

```php
<?php
// the username must be alphanumeric
// but not *only* numeric,
// at least 6 characters long,
// and cast it to a string
$filter->validate('username')->is('alnum');
$filter->validate('username')->isNot('numeric');
$filter->validate('username')->is('strlenMin', 6);
$filter->sanitize('username')->to('string');

// the password must be at least 6 characters long, and must match a
// confirmation field
$filter->validate('password')->is('strlenMin', 6);
$filter->validate('password_confirm')->is('equalToField', 'password');
?>
```

We can call `$filter->validate(...)->is(...)` and `isNot(...)`, as well as
`$filter->sanitize(...)->to(...)`, to specify how to filter the field.

### Applying The Filter

We can then apply the filter specifications to the subject. A `true` result
means all the rules passed, while `false` means one or more failed.

```php
<?php
// the data to be filtered; could also be an array
$data = (object) [
    'username' => 'bolivar',
    'password' => 'p@55w0rd',
    'password_confirm' => 'p@55word', // not the same!
];

// filter the object and see if there were failures
$success = $filter->apply($data);
if (! $success) {
    // get the failure messages
    $messages = $filter->getMessages();
    var_export($messages);
}
?>
```

### Failure Modes And Messages

Normally, the filter will stop filtering any field that fails one of
its rules, but will continue applying rules to the rest of the fields. Also,
the filter specification will provide a default message when a rule fails.

We can modify that behavior by specifying a failure mode, with an optional
custom message:

- `$filter->...->asSoftRule('custom message')` will cause the filter to keep
  applying rules to the field, even when a field rule fails.

- `$filter->...->asHardRule('custom message')` is the default behavior. If the
  rule fails, the filter will not apply any more rules to that field, but it
  will keep filtering other fields.

- `$filter->...->asStopRule('custom message')` will cause the filter to stop
  applying rules to all fields and return immediately if the rule fails. That
  is, the filter will not apply any more rules to any more fields.

In each case, the custom message will be used instead of the default one for
the specified rule.  If we want to just set a custom message without changing
the failure mode, we can use `$filter->...->setMessage('custom message').

If a field fails multiple rules, there will be multiple failure messages. To
specify a single failure message for a field, regardless of how many rules it
fails, call `$filter->useFieldMessage()`:

```php
<?php
$filter->validate('field')->isNot('blank')->asSoftRule();
$filter->validate('field')->is('alnum')->asSoftRule();
$filter->validate('field')->is('strlenMin', 6)->asSoftRule();
$filter->validate('field')->is('strlenMax', 12)->asSoftRule();

$filter->useFieldMessage('field', 'Please use 6-12 alphanumeric characters.');
?>
```

We can get the list of failure messages by calling `$filter->getMessages()`.

### Blank Values

The library incorporates the concept of "blank" values, as distinct from
`isset()` and `empty()`. A value is blank if it is `null`, an empty string, or
a string composed of only whitespace characters. Thus, the following are
blank:

```php
<?php
$blank = array(
    null,           // a null value
    '',             // an empty string
    " \r \n \t ",   // a whitespace-only string
);
?>
```

Integers, floats, booleans, and other non-strings are never counted as blank,
even if they evaluate to zero:

```php
<?php
$not_blank = array(
    0,                // integer
    0.00,             // float
    false,            // boolean false
    array(),          // empty array
    (object) array(), // an object
);
?>
```

#### Allowing For Blank Values

Generally, a blank field will fail its specified rule. To allow a field to pass
a specified rule even if it is blank, call `allowBlank()` on the specification:

```php
<?php
// both an alphanumeric value *and* a blank value will pass
$filter->validate('field')->is('alnum')->allowBlank();
?>
```

This leaves us with a special case for sanitizing, since if the value is blank,
we may wish to force it to a particular *kind* of blank value. We can call the
`useBlankValue()` method on the rule specification to sanitize blank values to
something else:

```php
<?php
// both an alphanumeric field *and* a blank field will pass
$filter->validate('field')->is('alnum')->allowBlank();
$filter->sanitize('field')->to('string')->useBlankValue('');
?>
```
Now if the field is not set or composed only of whitespace characters, it will
be modified to be an empty string. (If we call `useBlankValue()` with no
arguments, or call only `allowBlank()` with specifying a blank value,
blanks will be sanitized to `null`.)
