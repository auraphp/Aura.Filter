<?php
namespace Aura\Filter\Rule\Validate;

class IntTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return [
            ["+1234567890"],
            [1234567890],
            [-123456789.0],
            [-1234567890],
            ['-123'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [' '],
            [''],
            ["-abc.123"],
            ["123.abc"],
            ["123,456"],
            ['0000123.456000'],
        ];
    }
}
