<?php
namespace Aura\Filter\Rule\Sanitize;

class ClosureTest extends AbstractSanitizeTest
{
    protected function getArgs()
    {
        return array(function ($object, $field) {
            $value = $object->$field;
            $object->$field = (bool) $value;
            return true;
        });
    }

    public function providerTo()
    {
        return [
            [0, true, false],
            [1, true, true],
        ];
    }
}
