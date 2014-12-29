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
        return array(
            array(true),
            array(false),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(0),
            array(1),
            array(null),
        );
    }

    public function providerFix()
    {
        return array(
            array(0, true, false),
            array(1, true, true),
        );
    }
}
