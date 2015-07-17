<?php
namespace Aura\Filter\Rule\Validate;

class StrTest extends AbstractValidateTest
{
    public function ruleFix($rule)
    {
        return $rule->fix(' ', '@');
    }

    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr(' ', '@');
    }

    public function providerIs()
    {
        return array(
            array(12345),
            array(123.45),
            array(true),
            array(false),
            array('string'),
            array('абвдеж'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array(new \StdClass),
        );
    }
}
