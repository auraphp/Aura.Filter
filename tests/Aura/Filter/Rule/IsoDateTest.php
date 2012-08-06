<?php
namespace Aura\Filter\Rule;

class IsoDateTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_ISO_DATE';
    
    public function providerIs()
    {
        return [
            ['0001-01-01'],
            ['1970-08-08'],
            ['1979-11-07'],
            ['2004-02-29'],
            ['9999-12-31'],
            [[
                'Y' => '2012',
                'm' => '08',
                'd' => '14',
            ]]
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [' '],
            [''],
            ['1-2-3'],
            ['0001-1-1'],
            ['1-01-1'],
            ['1-1-01'],
            ['0000-00-00'],
            ['0000-01-01'],
            ['0010-20-40'],
            ['2005-02-29'],
            ['9999.12:31'],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['Nov 7, 1979, 12:34pm', '1979-11-07'],
            [
                [
                    'Y' => '2012',
                    'm' => '08',
                    'd' => '14',
                ],
                '2012-08-14',
            ],
            [strtotime('2012-08-02'), '2012-08-02'],
        ];
    }
}
