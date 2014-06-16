# Aura Filter

The Aura Filter package provides validation and sanitizing for data objects
and arrays.

## Foreword

### Installation

This library requires PHP 5.4 or later, and has no userland dependencies.

It is installable and autoloadable via Composer as [aura/filter](https://packagist.org/packages/aura/filter).

Alternatively, [download a release](https://github.com/auraphp/Aura.Filter/releases) 
or clone this repository, then require or include its _autoload.php_ file.
You don't need to run composer install in order to run the test suite.

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/auraphp/Aura.Filter/badges/quality-score.png?s=1c48d6875376b3c07dacf201b30fe997adeb6d15)](https://scrutinizer-ci.com/g/auraphp/Aura.Filter/)
[![Code Coverage](https://scrutinizer-ci.com/g/auraphp/Aura.Filter/badges/coverage.png?s=7ab1aace65d9b423b8e65dfe43ecea69b1f092dc)](https://scrutinizer-ci.com/g/auraphp/Aura.Filter/)
[![Build Status](https://travis-ci.org/auraphp/Aura.Filter.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.Filter)

To run the [PHPUnit][] tests at the command line, go to the _tests_ directory and issue `phpunit`.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PHPUnit]: http://phpunit.de/manual/
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura 
community, please join our [Google Group](http://groups.google.com/group/auraphp), 
follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.


## Getting Started

The easiest way to instantiate a new filter (i.e., a new `RuleCollection`)
with all the available rules is to use the `FilterFactory` class:

```php
<?php
$filter = (new FilterFactory())->newInstance();
```

Alternatively, we can add the `Aura.Filter` package to an autoloader, and
instantiate it manually:

```php
<?php
use Aura\Filter\RuleCollection as Filter;
use Aura\Filter\RuleLocator;

$filter = new Filter(new RuleLocator);
```

(Note that if we instantiate it manually, we will need to configure the
`RuleLocator` manually to add rule services. See the "Advanced Usage" section
near the end of this page for more information.)

Add rules for each field to the filter, then apply those rules to a data
object.

```php
<?php
// get a new filter
$filter = new Filter(new RuleLocator);

// the username must be alphanumeric, between 6 and 12 characters long,
// and cast to a string
$filter->addSoftRule('username', $filter::IS, 'alnum');
$filter->addSoftRule('username', $filter::IS, 'strlenBetween', 6, 12);
$filter->addSoftRule('username', $filter::FIX, 'string');

// the password must be at least 6 characters long, and must match a
// confirmation field
$filter->addSoftRule('password', $filter::IS, 'strlenMin', 6);
$filter->addSoftRule('password_confirm', $filter::IS, 'equalToField', 'password');

// the data object to be filtered; could also be an array
$data = (object) [
    'username' => 'bolivar',
    'password' => 'p@55w0rd',
    'password_confirm' => 'p@55word', // not the same!
];

// filter the object and see if there were failures
$success = $filter->values($data);
if (! $success) {
    $messages = $filter->getMessages();
    var_export($messages);
}
```


Applying Rules to Data Objects
==============================

Soft, Hard, and Stop Rules
--------------------------

There are three types of rule processing we can apply:

- The `addSoftRule()` method adds a soft rule: if the rule fails, the filter
  will keep applying all remaining rules to that field and all other fields.

- The `addHardRule()` method adds a hard rule: if the rule fails, the filter
  will not apply any more rules to that field, but it will keep filtering
  other fields.

- The `addStopRule()` method adds a stopping rule: if the rule fails, the
  filter will not apply any more filters to any more fields; this stops all
  filtering on the data object.


Validating and Sanitizing
-------------------------

We validate data by applying a rule with one of the following requirements:

- `RuleCollection::IS` means the field value must match the rule.

- `RuleCollection::IS_NOT` means the field value must *not* match the
  rule.

- `RuleCollection::IS_BLANK_OR` means the field value must *either* be
  blank, *or* match the rule. This is useful for optional field values that
  may or may not be filled in.

We sanitize data by applying a rule with one of the following transformations:

- `RuleCollection::FIX` to force the field value to comply with the
  rule; this may forcibly transform the value. Some transformations are not
  possible, so sanitizing the field may result in an error message.

- `RuleCollection::FIX_BLANK_OR` will convert blank values to `null`;
  non-blank fields will be forced to comply with the rule. This is useful for
  sanitizing optional field values that may or may not match the rule.

Each field is sanitized in place; i.e., the data object property will be
modified directly.


Blank Values
------------

Aura Filter incorporates the concept of "blank" values, as distinct from
`isset()` and `empty()`. A value is blank if it is `null`, an empty string, or
a string composed of only whitespace characters. Thus, the following are
blank:

```php
<?php
$blank = [
    null,           // a null value
    '',             // an empty string
    " \r \n \t ",   // a whitespace-only string
];
```

Integers, floats, booleans, and other non-strings are never counted as blank,
even if they evaluate to zero:

```php
<?php
$not_blank = [
    0,              // integer
    0.00,           // float
    false,          // boolean false
    [],             // empty array
    (object) [],    // an object
];
```

Available Rules
---------------

- `alnum`: Validates the value as alphanumeric only. Sanitizes to leave only
  alphanumeric characters. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'alnum');

- `alpha`: Validates the value as alphabetic only. Sanitizes to leave only
  alphabetic characters. Usage:
  
        $filter->addSoftRule('field', $filter::IS, 'alpha');

- `between`: Validates the value as being within or equal to a minimum and
  maximum value. Sanitizes so that values lower than the range are forced up
  to the minimum; values higher than the range are forced down to the maximum.
  Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'between', $min, $max);

- `blank`: Validates the value as being blank. Sanitizes to `null`. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'blank');

- `bool`: Validates the value as being a boolean, or a pseudo-boolean.
  Pseudo-true values include the strings '1', 'y', 'yes', and 'true';
  pseudo-false values include the strings '0', 'n', 'no', and 'false'.
  Sanitizes to a strict PHP boolean. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'bool');

- `creditCard`: Validates the value as being a credit card number. The value
  cannot be sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'creditCard');

- `dateTime`: Validates the value as representing a date and/or time. Sanitizes
  the value to a specified format, default `'Y-m-d H:i:s'`. Usage (note that
  this is to sanitize, not validate):
        
        $filter->addSoftRule('field', $filter::FIX, 'dateTime', $format);

- `email`: Validates the value as being a properly-formed email address. The
  value cannot be sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'email');

- `equalToField`: Validates the value as loosely equal to the value of another
  field in the data object. Sanitizes to the value of that other field.
  Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'equalToField', 'other_field_name');

- `equalToValue`: Validates the value as loosely equal to a specified value.
  Sanitizes to the specified value. Usage:

        $filter->addSoftRule('field', $filter::IS, 'equalToValue', $other_value);

- `float`: Validates the value as representing a float. Sanitizes the value to
  transform it into a float; for weird strings, this may not be what you
  expect. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'float');

- `inKeys`: Validates that the value is loosely equal to a key in a given
  array. The value cannot be sanitized. Usage:

        $filter->addSoftRule('field', $filter::IS, 'inKeys', $array);

- `inValues`: Validates that the value is strictly equal to at least one value
  in a given array. The value cannot be sanitized. Usage:

        $filter->addSoftRule('field', $filter::IS, 'inValues', $array);
        
- `int`: Validates the value as representing an integer. Sanitizes the value by
  transforming it into an integer; for weird strings, this may not be what you
  expect. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'int');

- `ipv4`: Validates the value as an IPv4 address. The value cannot be
  sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'ipv4');
        
- `locale`: Validates the given value against a list of locale strings. Returns false if it is
not found. The value cannot be sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'locale');

- `max`: Validates the value as being less than or equal to a maximum. Sanitizes
  so that values higher than the maximum are forced down to the maximum.
  Usage:

        $filter->addSoftRule('field', $filter::IS, 'max', $max);

- `min`: Validates the value as being greater than or equal to a minimum.
  Sanitizes so that values lower than the minimum are forced up to the
  minimum. Usage:

        $filter->addSoftRule('field', $filter::IS, 'min', $min);

- `regex`: Validates the value using `preg_match()`. Sanitizes the value using
  `preg_replace()`.

        $filter->addSoftRule('field', $filter::IS, 'regex', $expr);
        
- `strictEqualToField`: Validates the value as strictly equal to the value of
  another field in the data object. Sanitizes to the value of that other field.
  Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'strictEqualToField', 'other_field_name');

- `strictEqualToValue`: Validates the value as strictly equal to a specified
  value. Sanitizes to the specified value. Usage:

        $filter->addSoftRule('field', $filter::IS, 'strictEqualToValue', $other_value);

- `string`: Validates the value can be represented by a string. Sanitizes the
  value by casting to a string and optionally using `str_replace().` Usage
  (note that this is to sanitize, not validate):

        $filter->addSoftRule('field', $filter::FIX, 'string', $find, $replace);
    
- `strlen`: Validates the value has a specified length. Sanitizes the value
  to cut off longer values at the right, and `str_pad()` shorter ones. Usage:

        $filter->addSoftRule('field', $filter::IS, 'strlen', $len);

- `strlenBetween`: Validates the value length as being within or equal to a
  minimum and maximum value. Sanitizes the value to cut off values longer than
  the maximum, longer values at the right, and `str_pad()` shorter ones.
  Usage:

        $filter->addSoftRule('field', $filter::IS, 'strlenBetween', $min, $max);
        
- `strlenMax`: Validates the value length as being no longer than a maximum.
  Sanitizes the value to cut off values longer than the maximum. Usage:

        $filter->addSoftRule('field', $filter::IS, 'strlenMax', $max);
        
- `strlenMin`: Validates the value length as being no shorter than a minimum.
  Sanitizes the value to `str_pad()` values shorter than the minimum. Usage:

        $filter->addSoftRule('field', $filter::IS, 'strlenMin', $min);
        
- `trim`: Validates the value is `trim()`med. Sanitizes the value to `trim()` it.
  Optionally specify characters to trim. Usage:

        $filter->addSoftRule('field', $filter::IS, 'trim', $chars);
        
- `upload`: Validates the value represents a PHP upload information array, and
  that the file is an uploaded file. The value cannot be sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'upload');

- `url`: Validates the value is a well-formed URL. The value cannot be
  sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'url');

- `word`: Validates the value as being composed only of word characters.
 Sanitizes the value to remove non-word characters. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'word');

- `isbn`: Validates the value is a correct ISBN (International Standard Book Number). Usage:

        $filter->addSoftRule('field', $filter::IS, 'isbn');

- `any`: Validates the value passes at least one of the rules. These rules
are the ones added in the rule locator.
        
        $filter->addSoftRule('field', $filter::IS, 'any', [
                ['alnum'],
                ['email'],
                // more rules
            ]
        );
        
- `all`: Validates the value against a set of rules. These rules
should be added in the rule locator. You will not get separate error
messages for the rules it failed.
        
        $filter->addSoftRule('field', $filter::IS, 'all', [
                // rules
            ]
        );
        
Custom Messages
===============

By default when a rule fails, the messages you will be getting are predefined strings.
These can be translated accordingly with any translator. But you can also provide a single custom message for
all the failures.

```php
$filter->useFieldMessage('field', 'Custom Message');
```

Example:

```php
$filter->addSoftRule('username', $filter::IS, 'alnum');
$filter->addSoftRule('username', $filter::IS, 'strlenBetween', 6, 12);
$data = (object) [
    'username' => ' sds',
];

$filter->useFieldMessage('username', 'User name already exists');
// filter the object and see if there were failures
$success = $filter->values($data);
if (! $success) {
    $messages = $filter->getMessages();
    var_export($messages);
}
```

As you have used `useFieldMessage` you will see 

```php
array (
  'username' => 
  array (
    0 => 'User name already exists',
  ),
)
```

instead of 

```php
array (
  'username' => 
  array (
    0 => 'Please use only alphanumeric characters.',
    1 => 'Please use between 6 and 12 characters.',
  ),
)
```

Applying Rules to Individual Values
===================================

Normally, we use the filter with data objects and arrays. Alternatively, we
can apply a filter rule to an individual value:

```php
<?php
// get a new filter
$filter = new Filter(new RuleLocator);

// an individual value
$username = 'new_username';

// filter the individual value
$success = $filter->value($username, $filter::IS, 'alnum');
if (! $success) {
    echo "Username is not alphanumeric.";
}
```

> N.b.: The `value()` method must be applied to variables, not constants or
> literals, because of the way rule processing works under-the-hood.


Creating and Using Custom Rules
===============================

There are three steps to creating and using new rules:

1. Write a rule class

2. Set that class as a service in the `RuleLocator`

3. Use the new rule in our filter chain

Writing a Rule Class
--------------------

Writing a rule class is straightforward:

- Extend `Aura\Filter\AbstractRule` with two methods: `validate()` and
  `sanitize()`.

- Add params as needed to each method.

- Each method should return a boolean: true on success, or false on failure.

- Use `getValue()` to get the value being validated, and `setValue()` to change
  the value being sanitized.

- Add a property `$message` to indicate a string that should be translated
  as a message when validation or sanitizing fails.

Here is an example of a hexadecimal rule:

```php
<?php
namespace Vendor\Package\Filter\Rule;

use Aura\Filter\AbstractRule;

class Hex extends AbstractRule
{
    protected $message = 'FILTER_HEX';
    
    public function validate($max = null)
    {
        // must be scalar
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
    
        // must be hex
        $hex = ctype_xdigit($value);
        if (! $hex) {
            return false;
        }
    
        // must be no longer than $max chars
        if ($max && strlen($value) > $max) {
            return false;
        }
    
        // done!
        return true;
    }

    public function sanitize($max = null)
    {
        // must be scalar
        $value = $this->getValue();
        if (! is_scalar($value)) {
            // sanitizing failed
            return false;
        }
    
        // strip out non-hex characters
        $value = preg_replace('/[^0-9a-f]/i', '', $value);
        if ($value === '') {
            // failed to sanitize to a hex value
            return false;
        }
    
        // now check length and chop if needed
        if ($max && strlen($value) > $max) {
            $value = substr($value, 0, $max);
        }
    
        // retain the sanitized value, and done!
        $this->setValue($value);
        return true;
    }
}
```

Set The Class As A Service
--------------------------

Now we set the rule class into the `RuleLocator`.

```php
<?php
$locator = $filter->getRuleLocator();
$locator->set('hex', function () {
    return new Vendor\Package\Filter\Rule\Hex;
});
```

Apply The New Rule
------------------

Finally, we can use the rule in our filter:

```php
<?php
// the 'color' field must be a hex value of no more than 6 digits
$filter->addHardRule('color', $filter::IS, 'hex', 6);
```

That is all!
