<?php
namespace Aura\Filter\Rule;

class IsoTimeTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_ISO_TIME';
    
    public function providerIs()
    {
        return [
            ['00:00:00'],
            ['12:34:56'],
            ['23:59:59'],
            ['24:00:00'],
            [[
                'H' => '12',
                'i' => '34',
            ]],
            [[
                'H' => '12',
                'i' => '34',
                's' => '56',
            ]],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [' '],
            [''],
            ['24:00:01'],
            ['12.00.00'],
            ['12-34_56'],
            [' 12:34:56 '],
            ['  :34:56'],
            ['12:  :56'],
            ['12:34   '],
            ['12:34'],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['Nov 7, 1979, 12:34pm', '12:34:00'],
            [
                [
                    'H' => '17',
                    'i' => '28',
                    's' => '06',
                ],
                '17:28:06',
            ],
            [1343947049, '17:37:29'],
        ];
    }
}
