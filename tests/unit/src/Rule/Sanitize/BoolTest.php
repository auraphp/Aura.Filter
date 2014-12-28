<?php
namespace Aura\Filter\Rule\Sanitize;

class BoolTest extends AbstractSanitizeTest
{
    public function providerTo()
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
