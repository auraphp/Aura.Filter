<?php
namespace Aura\Filter\Rule\Validate;

class Hex
{
    public function __invoke($object, $field, $max = null)
    {
        $value = $object->$field;

        // must be scalar
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

    // public function sanitize($max = null)
    // {
    //     // must be scalar
    //     $value = $this->getValue();
    //     if (! is_scalar($value)) {
    //         // sanitizing failed
    //         return false;
    //     }

    //     // strip out non-hex characters
    //     $value = preg_replace('/[^0-9a-f]/i', '', $value);
    //     if ($value === '') {
    //         // failed to sanitize to a hex value
    //         return false;
    //     }

    //     // now check length and chop if needed
    //     if ($max && strlen($value) > $max) {
    //         $value = substr($value, 0, $max);
    //     }

    //     // retain the sanitized value, and done!
    //     $this->setValue($value);

    //     return true;
    // }
}
