## Creating and Using Custom Rules

There are three steps to creating and using new rules:

1. Write a rule class

2. Set that class as a service in the `RuleLocator`

3. Use the new rule in our filter chain

### Writing a Rule Class

Writing a rule class is straightforward:

- Extend `Aura\Filter\AbstractRule` with two methods: `validate()` and
  `sanitize()`.

- Add params as needed to each method.

- Each method should return a boolean: true on success, or false on failure.

- Use `getValue()` to get the value being validated, and `setValue()` to change
  the value being sanitized.

- Add a property `$message` to indicate a string that should be returned
  as a message when validation or sanitizing fails.

Here is an example of a hexadecimal rule:

```php
<?php
namespace Vendor\Package\Filter\Rule;


class Hex extends AbstractRule
{
    protected $message = 'FILTER_HEX';

    public function validate($object, $field, $max = null)
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
?>
```

### Set The Class As A Service

Now we set the rule class into the `RuleLocator`.

```php
<?php
$locator = $filter->getRuleLocator();
$locator->set('hex', function () {
    return new Vendor\Package\Filter\Rule\Hex;
});
?>
```

### Apply The New Rule

Finally, we can use the rule in our filter:

```php
<?php
// the 'color' field must be a hex value of no more than 6 digits
$filter->addHardRule('color', $filter::IS, 'hex', 6);
```
