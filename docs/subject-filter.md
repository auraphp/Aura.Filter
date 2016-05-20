# Filtering Arrays and Objects

You can filter an entire object or array (herein called a "subject") by specifying rules for each object property or array element (herein called "fields").

## Creating A Subject Filter

First, create a _SubjectFilter_ via the _FilterFactory_:

```php
$filter_factory = new FilterFactory();

$filter = $filter_factory->newSubjectFilter();
```

Next, add rule specifications to validate and/or sanitize each subject field:

```php
// the username must be alphanumeric
// but not *only* numeric,
// at least 6 characters long,
// and cast it to a string
$filter->validate('username')->is('alnum');
$filter->validate('username')->isNot('int');
$filter->validate('username')->is('strlenMin', 6);
$filter->sanitize('username')->to('string');

// the password must be at least 6 characters long, and must match a
// confirmation field
$filter->validate('password')->is('strlenMin', 6);
$filter->validate('password_confirm')->is('equalToField', 'password');
```

We can call one of the following methods after `validate()`:

- `is(...)` to specify that the value **must** match the rule
- `isNot(...)` to specify that the value **must not** match the rule
- `isBlank()` to specify that the value **must** be blank (note that using other rules with `isBlank()` may not make sense)
- `isBlankOr(...)` to specify that the value may be blank, or that it **must**
  match the rule
- `isBlankOrNot(...)` to specify that the value may be blank, or that it
  **must not** match the rule
- `isNotBlank()` to specifiy that the value **must not** be blank (note that this makes sense only if you don't care about other validations)

We can call one of the following methods after `sanitize()`:

- `to(...)` to specify the value should be changed according to the rule
- `toBlankOr(...)` to specify that a blank field should be changed to `null`,
  and that non-blank fields should be changed according to the rule
- `useBlankValue(...)` to specify what value blank fields should be changed to (default `null`)

For more about blanks, see the section on [Blank Fields](#blank-fields).

## Applying The Subject Filter

We can then apply the filter specifications to the subject. A `true` result
means all the rules passed, while `false` means one or more failed.

```php
// the data to be filtered; could also be an object
$subject = array(
    'username' => 'bolivar',
    'password' => 'p@55w0rd',
    'password_confirm' => 'p@sword', // not the same!
);

// filter the object and see if there were failures
$success = $filter->apply($subject);
if (! $success) {
    // get the failures
    $failures = $filter->getFailures();
    echo $failures->getMessages();
}
```

## Filter Failures

When we get the failures via `getFailures()`, we can examine in detail which fields failed, and what the failure messages were. The `getFailures()` method returns a _FailureCollection_ (essentially an _ArrayObject_ keyed on the field names). Each field in the _FailureCollection_ has an array of _Failure_ objects, each with these methods:

- `Failure::getField()` -- the field that failed
- `Failure::getMessage()` -- the failure message
- `Failure::getArgs()` -- arguments passed to the rule specification

These can be combined in various ways to generate output regarding the filter failures.


## Failure Modes

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
the failure mode, we can use `$filter->...->setMessage('custom message')`.


## Field-Specific Failure Messages

If a field fails multiple rules, there will be multiple failure messages (one for each failed rule). To specify a single failure message for a field, regardless of which rule(s) it fails, call `$filter->useFieldMessage()`:

```php
$filter->validate('field')->isNot('blank')->asSoftRule();
$filter->validate('field')->is('alnum')->asSoftRule();
$filter->validate('field')->is('strlenMin', 6)->asSoftRule();
$filter->validate('field')->is('strlenMax', 12)->asSoftRule();

$filter->useFieldMessage('field', 'Please use 6-12 alphanumeric characters.');
```


## Blank Fields

This library incorporates the concept of "blank" fields, as distinct from
`isset()` and `empty()`, to allow for input elements that are missing or have
not been filled in. A field is blank if it is:

- not set in the subject being filtered,
- set to `null`,
- an empty string (''), or
- a string composed of only whitespace characters.

Integers, floats, booleans, resources, arrays, and objects are never "blank"
even if they evaluate to zero or are empty:

```php
$not_blank = array(
    0,                // integer
    0.00,             // float
    false,            // boolean false
    array(),          // empty array
    new StdClass,     // an object
);
```

Generally, a blank field will fail to validate. To allow a validate rule to pass
even if the field is blank, call `isBlankOr()` or `isBlankOrNot()` on its rule
specification:

```php
// either an alphanumeric value *or* a blank value will validate
$filter->validate('field')->isBlankOr('alnum');
```

Likewise, a blank field may fail to sanitize properly. To allow for a blank
field with a sanitize rule, call `toBlankOr()` on its rule specification:

```php
// both an alphanumeric field *and* a blank field will pass
$filter->sanitize('field')->toBlankOr('alnum');
```

This will cause blank values to be sanitized to `null`, and non-blank values
to be sanitized using the `alnum` rule.

Finally, if we want blank values to be sanitized to something other than
`null`, call `useBlankValue()` to specify the value to use when blank:

```php
// both an alphanumeric field *and* a blank field will pass
$filter->sanitize('field')->toBlankOr('alnum')->useBlankValue('');
```

That will cause blank values to be sanitized to an empty string. Additionally,
please note that `useBlankValue()` implies `toBlankOr()`, so the following has
the same effect as the above:

```php
// both an alphanumeric field *and* a blank field will pass
$filter->sanitize('field')->to('alnum')->useBlankValue('');
```

## Extending And Initializing A Subject Filter

Sometimes it may be useful to extend the _Filter_ class for a specific purpose, one that can initialize itself. This can be useful when filtering a specific kind of object or dataset.

To do so, override the the `init()` method on the extended _Filter_ class; the above examples remain instructive, but use `$this` instead of `$filter` since you are working from inside the filter object:

```php
namespace Vendor\Package;

use Aura\Filter\SubjectFilter;

class EntityFilter extends SubjectFilter
{
    protected function init()
    {
        $this->validate('field')->isNot('blank')->asSoftRule();
        $this->validate('field')->is('alnum')->asSoftRule();
        $this->validate('field')->is('strlenMin', 6)->asSoftRule();
        $this->validate('field')->is('strlenMax', 12)->asSoftRule();

        $this->useFieldMessage('field', 'Please use 6-12 alphanumeric characters.');
    }
}
```

You can then create a new instance of your extended filter class through the _FilterFactory_:

```php
$entity_filter = $filter_factory->newSubjectFilter('Vendor\Package\EntityFilter');
$success = $entity_filter->apply($entity);
```

## Asserting or Invoking the Filter

Whereas calling `$filter->apply($subject)` returns a boolean, calling `$filter->assert($subject)` returns `null` on success, and throws an exception on failure. (Invoking the filter as a callable a la `$filter($subject)` works the same as `assert()`.)

```php
use Aura\Filter\Exception\FilterFailed;

// the data to be filtered; could also be an object
$subject = array(
    'username' => 'bolivar',
    'password' => 'p@55w0rd',
    'password_confirm' => 'p@55word', // not the same!
);

// filter the object and see if there were failures
try {
    $filter($subject);
} catch (FilterFailed $e)
    // ...
}
```

The _FilterFailed_ exception has these methods in addition to the normal _Exception_ methods:

- `getFilterClass()` -- returns the class of the filter being used, in case you were using a custom filter class
- `getSubject()` -- returns the subject being filtered
- `getFailures()` -- returns the _FailureCollection_
