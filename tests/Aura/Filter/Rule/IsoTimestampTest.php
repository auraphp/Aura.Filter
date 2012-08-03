<?php
namespace Aura\Filter\Rule;

class IsoTimestampTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_ISO_TIMESTAMP';
    
    public function providerIs()
    {
        return [
            ['0001-01-01 00:00:00'],
            ['1970-08-08 12:34:56'],
            ['2004-02-29 24:00:00'],
            [[
                'Y' => '1979',
                'm' => '11',
                'd' => '07',
                'H' => '23',
                'i' => '45',
                's' => '16',
            ]],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [' '],
            [''],
            ['0000-00-00T00:00:00'],
            ['0000-01-01T12:34:56'],
            ['0010-20-40T12:34:56'],
            ['1979-11-07T12:34'],
            ['1970-08-08t12:34:56'],
            ['           24:00:00'],
            ['9999-12-31         '],
            ['9999.12:31 ab:cd:ef'],
            [[]],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['Nov 7, 1979, 12:34pm', '1979-11-07 12:34:00'],
            [
                [
                    'Y' => '2001',
                    'm' => '07',
                    'd' => '19',
                    'H' => '17',
                    'i' => '28',
                    's' => '06',
                ],
                '2001-07-19 17:28:06',
            ],
            [1343947049, '2012-08-02 17:37:29'],

        ];
    }
}
