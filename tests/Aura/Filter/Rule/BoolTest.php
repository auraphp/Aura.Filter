<?php
namespace Aura\Filter\Rule;

class BoolTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_BOOL';
    
    public function providerIs()
    {
        return [
            [true],
            ['on'],
            ['On'],
            ['ON'],
            ['yes'],
            ['Yes'],
            ['YeS'],
            ['y'],
            ['Y'],
            ['true'],
            ['True'],
            ['TrUe'],
            ['t'],
            ['T'],
            [1],
            ['1'],
            [false],
            ['off'],
            ['Off'],
            ['OfF'],
            ['no'],
            ['No'],
            ['NO'],
            ['n'],
            ['N'],
            ['false'],
            ['False'],
            ['FaLsE'],
            ['f'],
            ['F'],
            [0],
            ['0'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            ['nothing'],
            [123],
        ];
    }
    
    public function providerFix()
    {
        return [
            // sanitize to true
            [true, true],
            ['on', true],
            ['On', true],
            ['ON', true],
            ['yes', true],
            ['Yes', true],
            ['YeS', true],
            ['y', true],
            ['Y', true],
            ['true', true],
            ['True', true],
            ['TrUe', true],
            ['t', true],
            ['T', true],
            [1, true],
            ['1', true],
            ['not empty', true],
            // sanitize to false
            [false, false],
            ['off', false],
            ['Off', false],
            ['OfF', false],
            ['no', false],
            ['No', false],
            ['NO', false],
            ['n', false],
            ['N', false],
            ['false', false],
            ['False', false],
            ['FaLsE', false],
            ['f', false],
            ['F', false],
            [0, false],
            ['0', false],
        ];
    }
}
