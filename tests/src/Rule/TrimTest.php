<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class TrimTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_TRIM';

    public function providerIs()
    {
        return [
            ['abc'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            [' abc '],
        ];
    }

    public function providerFix()
    {
        return [
            [array(), false, array()],
            [' abc ', true, 'abc'],
        ];
    }
}
