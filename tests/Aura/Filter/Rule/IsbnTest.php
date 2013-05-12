<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class IsbnTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_ISBN';

    public function providerIs()
    {
        return [
            ['3-7814-0334-3'],
            ['3-8053-1903-7'],
            ['960-7037-43-X'],
            ['88-435-6938-4'],
            ['3836211394'],
            ['978-3836211390'],
            ['978-3836211390'],
            ['isbn 88-435-6938-4'],
            ['0201633612'],
            ['978-0201633610'],
            ['80-902734-1-6'],
            ['85-359-0277-5'],
            ['99921-58-10-7'],
            ['960-425-059-0'],
            ['0-8044-2957-X'],
            ['0-9752298-0-X']
        ];
    }

    public function providerIsNot()
    {
        return [
            ['978-3836211391'],
            ['978-3836211391'],
            ['isbn'],
            ['3836211397']
        ];
    }

    public function providerFix()
    {
        return [
            // sanitize to true
            ['3836211394',      true, '3836211394'],
            ['3-7814-0334-3',   true, '3781403343'],
            ['960-7037-43-X',   true, '960703743X'],

            // sanitize to false
            ['isbn',            false, 'isbn'],
            ['960-7037-43-x',   false, '960-7037-43-x'],
        ];
    }
}
