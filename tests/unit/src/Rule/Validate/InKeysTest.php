<?php
namespace Aura\Filter\Rule\Validate;

class InKeysTest extends AbstractValidateTest
{
    protected $opts = [
        0      => 'val0',
        1      => 'val1',
        'key0' => 'val3',
        'key1' => 'val4',
        'key2' => 'val5',
    ];

    protected function getArgs()
    {
        $args = parent::getArgs();
        $args[] = $this->opts;
        return $args;
    }

    public function providerIs()
    {
        return [
            [0],
            [1],
            ['key0'],
            ['key1'],
            ['key2'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [null],
            [false],
            [''],
            [1.2],
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
