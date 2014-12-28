<?php
namespace Aura\Filter\Rule\Validate;

class FloatTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return [
            ["+123456.7890"],
            [12345.67890],
            [-123456789.0],
            [-123.4567890],
            ['-1.23'],
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
            ['00.00123.4560.00'],
        ];
    }
}
