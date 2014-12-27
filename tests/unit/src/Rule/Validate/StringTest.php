<?php
namespace Aura\Filter\Rule\Validate;

class StringTest extends AbstractValidateTest
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
        return [
            [12345],
            [123.45],
            [true],
            [false],
            ['string'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            [new \StdClass],
        ];
    }

    public function providerFix()
    {
        return [
            ['abc 123 ,./', true, 'abc@123@,./'],
            [12345, true, '12345'],
        ];
    }
}
