<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class BlankTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_BLANK';

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
