Aura Filter
===========

The Aura Filter package provides validation and sanitizing for data objects
and arrays.

This package is compliant with [PSR-0][], [PSR-1][], and [PSR-2][]. If you
notice compliance oversights, please send a patch via pull request.

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md


Getting Started
===============

The easiest way to instantiate a new filter (i.e., a new `RuleCollection`)
with all the available rules is to include the `instance.php` script:

```php
<?php
$filter = require "/path/to/Aura.Filter/scripts/instance.php";
```

Alternatively, we can add the `Aura.Filter` package to an autoloader, and
instantiate manually:

```php
<?php
use Aura\Filter\RuleCollection as Filter;
use Aura\Filter\RuleLocator;

$filter = new Filter(new RuleLocator);
```

(Note that if we instantiate manually, we will need to configure the
`RuleLocator` manually to add rule services. See the "Advanced Usage" section
near the end of this page for more information.)

Add rules for each field to the filter, then apply those rules to a data
object.

```php
<?php
// get a new filter
$filter = require "/path/to/Aura.Filter/scripts/instance.php";

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

- `alnum`: Validate the value as alphanumeric only. Sanitize to leave only
  alphanumeric characters. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'alnum');

- `alpha`: Validate the value as alphabetic only. Sanitize to leave only
  alphabetic characters. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'alpha');

- `between`: Validate the value as being within or equal to a minimum and
  maximum value. Sanitize so that values lower than the range are forced up
  to the minimum; values higher than the range are forced down to the maximum.
  Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'between', $max, $min);

- `blank`: Validate the value as being blank. Sanitize to `null`. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'blank');

- `bool`: Validate the value as being a boolean, or a pseudo-boolean.
  Pseudo-true values include the strings '1', 'y', 'yes', and 'true';
  pseudo-false values include the strings '0', 'n', 'no', and 'false'.
  Sanitize to a strict PHP boolean. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'bool');

- `creditCard`: Validate the value as being a credit card number. The value
  cannot be sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'creditCard');

- `dateTime`: Validate the value as representing a date and/or time. Sanitize
  the value to a specified format, default `'Y-m-d H:i:s'`. Usage (note that
  this is to sanitize, not validate):
        
        $filter->addSoftRule('field', $filter::FIX, 'dateTime', $format);

- `email`: Validate the value as being a properly-formed email address. The
  value cannot be sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'email');

- `equalToField`: Validate the value as loosely equal to the value of another
  field in the data object. Sanitize to the value of that other field.
  Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'equalToField', 'other_field_name');

- `equalToValue`: Validate the value as loosely equal to a specified value.
  Sanitize to the specified value. Usage:

        $filter->addSoftRule('field', $filter::IS, 'equalToValue', $other_value);

- `float`: Validate the value as representing a float. Sanitize the value to
  transform it into a float; for weird strings, this may not be what you
  expect. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'float');

- `inKeys`: Validate that the value is loosely equal to a key in a given
  array. The value cannot be sanitized. Usage:

        $filter->addSoftRule('field', $filter::IS, 'inKeys', $array);

- `inValues`: Validate that the value is strictly equal to at least one value
  in a given array. The value cannot be sanitized. Usage:

        $filter->addSoftRule('field', $filter::IS, 'inValues', $array);
        
- `int`: Validate the value as representing an integer Sanitize the value to
  transform it into an integer; for weird strings, this may not be what you
  expect. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'int');

- `ipv4`: Validate the value as an IPv4 address. The value cannot be
  sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'ipv4');
        
- `locale`: Validate the given value against a list of locale strings. If it's 
not found returns false. The value cannot be sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'locale');

- `max`: Validate the value as being less than or equal to a maximum. Sanitize
  so that values higher than the maximum are forced down to the maximum.
  Usage:

        $filter->addSoftRule('field', $filter::IS, 'max', $max);

- `min`: Validate the value as being greater than or equal to a minimum.
  Sanitize so that values lower than the minimum are forced up to the
  minimum. Usage:

        $filter->addSoftRule('field', $filter::IS, 'min', $min);

- `regex`: Validate the value using `preg_match()`. Sanitize the value using
  `preg_replace()`.

        $filter->addSoftRule('field', $filter::IS, 'regex', $expr);
        
- `strictEqualToField`: Validate the value as strictly equal to the value of
  another field in the data object. Sanitize to the value of that other field.
  Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'strictEqualToField', 'other_field_name');

- `strictEqualToValue`: Validate the value as strictly equal to a specified
  value. Sanitize to the specified value. Usage:

        $filter->addSoftRule('field', $filter::IS, 'strictEqualToValue', $other_value);

- `string`: Validate the value can be represented by a string. Sanitize the
  value by casting to a string and optionally using `str_replace().` Usage
  (note that this is to sanitize, not validate):

        $filter->addSoftRule('field', $filter::FIX, 'string', $find, $replace);
    
- `strlen`: Validate the value has a specified length. Sanitize the value
  to cut off longer values at the right, and `str_pad()` shorter ones. Usage:

        $filter->addSoftRule('field', $filter::IS, 'strlen', $len);

- `strlenBetween`: Validate the value length as being within or equal to a
  minimum and maximum value. Sanitize the value to cut off values longer than
  the maximum, longer values at the right, and `str_pad()` shorter ones.
  Usage:

        $filter->addSoftRule('field', $filter::IS, 'strlenBetween', $min, $max);
        
- `strlenMax`: Validate the value length as being no longer than a maximum.
  Sanitize the value to cut off values longer than the maximum. Usage:

        $filter->addSoftRule('field', $filter::IS, 'strlenMax', $max);
        
- `strlenMin`: Validate the value length as being no shorter than a minimum.
  Sanitize the value to `str_pad()` values shorter than the minimum. Usage:

        $filter->addSoftRule('field', $filter::IS, 'strlenMin', $min);
        
- `trim`: Validate the value is `trim()`med. Sanitize the value to `trim()` it.
  Optionally specify characters to trim. Usage:

        $filter->addSoftRule('field', $filter::IS, 'trim', $chars);
        
- `upload`: Validate the value represents a PHP upload information array, and
  that the file is an uploaded file. The value cannot be sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'upload');

- `url`: Validate the value is a well-formed URL. The value cannot be
  sanitized. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'url');

- `word`: Validate the value as being composed only of word characters.
 Sanitize the value to remove non-word characters. Usage:
        
        $filter->addSoftRule('field', $filter::IS, 'word');


Applying Rules to Individual Values
===================================

Normally, we use the filter with data objects and arrays. Alternatively, we
can apply a filter rule to an individual value:

```php
<?php
// get a new filter
$filter = require "/path/to/Aura.Filter/scripts/instance.php";

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
