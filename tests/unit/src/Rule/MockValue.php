<?php
namespace Aura\Filter\Rule;

class MockValue
{
    public function validate($object, $field, $return)
    {
        return $return;
    }

    public function sanitize($return)
    {
        return $return;
    }
}
