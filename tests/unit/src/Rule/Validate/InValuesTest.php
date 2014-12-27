<?php
namespace Aura\Filter\Rule\Validate;

class InValuesTest extends AbstractValidateTest
{
    protected $opts = [
        0      => 'val0',
        1      => 'val1',
        'key0' => 'val3',
        'key1' => 'val4',
        'key2' => 'val5',
    ];

    public function getArgs()
    {
        return array($this->opts);
    }

    public function providerIs()
    {
        return [
            ['val0'],
            ['val1'],
            ['val3'],
            ['val4'],
            ['val5'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [3],
            [4],
            ['a'],
            ['b'],
            ['c'],
        ];
    }

    public function providerFix()
    {
        return [
            ['no-good', false, 'no-good'], // cannot fix
        ];
    }
}
