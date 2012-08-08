Aura Filter
===========

The Aura Filter package provides validation and sanitizing for data objects.

This package is compliant with [PSR-0][], [PSR-1][], and [PSR-2][]. If you
notice compliance oversights, please send a patch via pull request.

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md


Getting Started
===============

The easiest way to instantiate a new filter object with all the available
rules is to include the `instance.php` script:

    <?php
    $filter = require "/path/to/Aura.Filter/scripts/instance.php";

Alternatively, we can add the `Aura.Filter` package to an autoloader, and
instantiate manually:

    <?php
    use Aura\Filter\Chain;
    use Aura\Filter\RuleLocator;
    $filter = new Chain(new RuleLocator);

(Note that if we instantiate manually, we will need to configure the
`RuleLocator` manually to add rule services. See the "Rules" section
near the end of this page for more information.)

Add field rules to the filter, then apply those rules to a data object.

    <?php
    use Aura\Filter\Value;
    
    // the username must be alphanumeric, between 6 and 12 characters long,
    // and cast to a string
    $filter->addSoftRule('username', Value::IS, 'alnum');
    $filter->addSoftRule('username', Value::IS, 'strlenBetween', 6, 12);
    $filter->addSoftRule('username', Value::FIX, 'string');
    
    // the password must be at least 6 characters long, and must match a
    // confirmation field
    $filter->addSoftRule('password', Value::IS, 'strlenMin', 6);
    $filter->addSoftRule('password_confirm', Value::IS, 'equalToField', 'password');
    
    // the data object to be filtered:
    $data = (object) [
        'username' => 'bolivar',
        'password' => 'p@55w0rd',
        'password_confirm' => 'p@55word',
    ];
    
    // filter the object and see if there were failures
    $success = $filter->exec($data);
    if (! $success) {
        $messages = $filter->getMessages();
        var_export($messages);
    }

Applying Rules
==============

Soft, Hard, and Stop Rules
--------------------------

There are three types of rule processing we can apply:

- The `addSoftRule()` method adds a soft rule: if the rule fails, the filter
  will keep applying the the remaining rules to that field and all other
  fields.

- The `addHardRule()` method adds a hard rule: if the rule fails, the filter
  will not apply any more filters to that field, but it will keep filtering
  other fields.

- The `addStopRule()` method adds a stopping rule: if the rules fails, the
  filter will not apply any more filters to any more fields; this stops all
  filtering on the data object.


Validating and Sanitizing
-------------------------

We validate data by applying a rule with one of the following requirements:

- `Value::IS` means the field value must match the rule.

- `Value::IS_NOT` means the field value must *not* match the rule.

- `Value::IS_BLANK_OR` means the field value must *either* be blank, *or*
  match the rule. This is useful for optional field values that may or may not
  be filled in.

We sanitize data by applying a rule with one of the following transformations:

- `Value::FIX` to force the field value to comply with the rule; this may
  forcibly transform the value. Some transformations are not possible, so
  sanitizing the field may result in an error message.

- `Value::FIX_BLANK_OR` will convert blank values to `null`; non-blank fields
  will be forced to comply with the rule. This is useful for sanitizing
  optional field values that may or may not match the rule.

Each field is sanitized in place; i.e., the data object property will be
modified directly.

Blank Values
------------

Aura Filter uses the concept of "blank" values, as distinct from `isset()` and
`empty()`. A value is blank if it is `null`, an empty string, or a string
composed of only whitespace characters.  Thus, the following are blank:

    $blank = [
        // a null value
        null,
        // an empty string
        '',
        // a whitespace-only string
        " \r \n \t ",
    ];

Integers, floats, booleans, and other non-strings are never counted as blank,
even if they evaluate to zero:

    $not_blank = [
        // integer
        0,
        // float
        0.00,
        // boolean false
        false,
        // empty array
        [],
        // an object
        (object) [],
    ];

Available Rules
---------------

- `alnum`: Validate the value as alphanumeric only. Sanitize to leave only
  alphanumeric characters.

- `alpha`: Validate the value as alphabetic only. Sanitize to leave only
  alphabetic characters.

- `between`: Validate the value as being within or equal to a minimum and
  maximum value. Sanitize to that values lower than the range are forced up
  to the minimum; values higher than the range are forced down to the maximum.

- `blank`: Validate the value as being blank. Sanitize to `null`.

- `bool`: Validate the value as being a boolean, or a pseudo-boolean.
  Pseudo-true values include the strings '1', 'y', 'yes', and 'true';
  pseudo-false values include the strings '0', 'n', 'no', and 'false'.
  Sanitize to a strict PHP boolean.
  
- `creditCard`: Validate the value as being a credit card number. The value
  cannot be sanitized.

- `dateTime`:

- `email`:

- `equalToField`:

- `equalToValue`:

- `float`:

- `inKeys`:

- `inValues`:

- `int`:

- `ipv4`:

- `max`:

- `min`:

- `regex`:

- `strictEqualToField`:

- `strictEqualToValue`:

- `string`:

- `strlen`:

- `strlenBetween`:

- `strlenMax`:

- `strlenMin`:

- `trim`:

- `upload`:

- `url`:

- `word`:

* * *