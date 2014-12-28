<?php
namespace Aura\Filter\Rule\Validate;

class ClosureTest extends AbstractValidateTest
{
    protected function getArgs()
    {
        return array(function ($object, $field) {
            return is_bool($object->$field);
        });
    }

    public function providerIs()
    {
        return [
            [true],
            [false],
        ];
    }

    public function providerIsNot()
    {
        return [
            [0],
            [1],
            [null],
        ];
    }

    public function providerFix()
    {
        return [
            [0, true, false],
            [1, true, true],
        ];
    }
}
