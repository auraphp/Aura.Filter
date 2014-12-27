<?php
namespace Aura\Filter\Rule\Validate;

class EmailTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return [
            ["pmjones@solarphp.net"],
            ["no.body@no.where.com"],
            ["any-thing@gmail.com"],
            ["any_one@hotmail.com"],
            ["nobody1234567890@yahoo.co.uk"],
            ["something+else@example.com"],
        ];
    }

    public function providerIsNot()
    {
        return [
            ["something @ somewhere.edu"],
            ["the-name.for!you"],
            ["non:alpha@example.com"],
            [""],
            ["\t\n"],
            [" "],
        ];
    }

    public function providerFix()
    {
        return [
            ["non:alpha@example.com", false, "non:alpha@example.com"], // can't fix
        ];
    }
}
