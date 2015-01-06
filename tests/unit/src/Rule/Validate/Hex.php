<?php
namespace Aura\Filter\Rule\Validate;

class Hex
{
    public function __invoke($subject, $field, $max = null)
    {
        $value = $subject->$field;

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
}
