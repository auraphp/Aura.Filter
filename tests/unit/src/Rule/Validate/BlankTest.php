<?php
namespace Aura\Filter\Rule\Validate;

class BlankTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return [
            [""],
            [" "],
            ["\t"],
            ["\n"],
            ["\r"],
            [" \t \n \r "],
        ];
    }

    public function providerIsNot()
    {
        return [
            [0],
            [1],
            ['0'],
            ['1'],
            ["Seven 8 nine"],
            ["non:alpha-numeric's"],
            ['someThing8else'],
        ];
    }

    public function providerFix()
    {
        return [
            ["",                true, null],
            [" ",               true, null],
            ["\t",              true, null],
            ["\n",              true, null],
            ["\r",              true, null],
            [" \t \n \r ",      true, null],
        ];
    }
}
