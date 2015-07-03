# Creating and Using Custom Rules

There are three steps to creating and using new rules:

1. Write a rule class, either to validate or sanitize a subject field

2. Set a factory for the class in the appropriate rule locator

3. Use the new rule in a filter specification

## Writing a Rule Class

Writing a rule class is straightforward.  Define a class with an
`__invoke($subject, $field)` method, along with any additional needed arguments.
The method should return a boolean: true on success, or false on failure.

### A Validate Class

Here is an example of a hexdecimal validator:

```php
<?php
namespace Vendor\Package\Filter\Rule\Validate;

class ValidateHex
{
    public function __invoke($subject, $field, $max = null)
    {
        // must be scalar
        $value = $subject->$field;
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
}
?>
```

### A Sanitize Class

Here is an example of a hexadecimal sanitizer. Note how we modify the
`$subject->$field` value directly at the end of the method.

```php
<?php
namespace Vendor\Package\Filter\Rule\Sanitize;

class SanitizeHex
{
    public function __invoke($subject, $field, $max = null)
    {
        $value = $subject->$field;

        // must be scalar
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
        $subject->$field = $value;
        return true;
    }
}
?>
```

## Set A Factory In The Locator

Now we set a factory for the rule into the appropriate locator from the
_FilterContainer_. Validate rules should go in the _ValidateLocator_, and
sanitize rules should go in the _SanitizeLocator_. Wrap the rule instantion
logic in a closure so that it is lazy-loaded only when the rule is called.

```php
<?php
use Aura\Filter\FilterContainer;

$filter_factory = new FilterContainer();

$validate_locator = $filter_factory->getValidateLocator();
$validate_locator->set('hex', function () {
    return new Vendor\Package\Filter\Rule\Validate\ValidateHex();
});

$sanitize_locator = $filter_factory->getSanitizeLocator();
$sanitize_locator->set('hex', function () {
    return new Vendor\Package\Filter\Rule\Sanitize\SanitizeHex();
});
?>
```

## Apply The New Rule

Finally, we can use the rule in our filter:

```php
<?php
$filter = $filter_factory->newFilter();

// the 'color' field must be a hex value of no more than 6 digits
$filter->validate('color')->is('hex', 6);
```
