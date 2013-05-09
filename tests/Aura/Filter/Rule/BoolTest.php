<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class BoolTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_BOOL';
    
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
            [true,          true, true],
            ['on',          true, true],
            ['On',          true, true],
            ['ON',          true, true],
            ['yes',         true, true],
            ['Yes',         true, true],
            ['YeS',         true, true],
            ['y',           true, true],
            ['Y',           true, true],
            ['true',        true, true],
            ['True',        true, true],
            ['TrUe',        true, true],
            ['t',           true, true],
            ['T',           true, true],
            [1,             true, true],
            ['1',           true, true],
            ['not empty',   true, true],
            // sanitize to false
            [false,         true, false],
            ['off',         true, false],
            ['Off',         true, false],
            ['OfF',         true, false],
            ['no',          true, false],
            ['No',          true, false],
            ['NO',          true, false],
            ['n',           true, false],
            ['N',           true, false],
            ['false',       true, false],
            ['False',       true, false],
            ['FaLsE',       true, false],
            ['f',           true, false],
            ['F',           true, false],
            [0,             true, false],
            ['0',           true, false],
        ];
    }
}
