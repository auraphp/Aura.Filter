<?php
namespace Aura\Filter\Rule\Sanitize;

class Hex
{
    public function __invoke($subject, $field, $max = null)
    {
        $value = $subject->$field;

        // must be scalar
        if (! is_scalar($value)) {
            return false;
        }

        // strip out non-hex characters
        $value = preg_replace('/[^0-9a-f]/i', '', $value);
        if ($value === '') {
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
