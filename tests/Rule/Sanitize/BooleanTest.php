<?php
namespace Aura\Filter\Rule\Sanitize;

class BooleanTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            // sanitize to true
            array(true,          true, true),
            array('on',          true, true),
            array('On',          true, true),
            array('ON',          true, true),
            array('yes',         true, true),
            array('Yes',         true, true),
            array('YeS',         true, true),
            array('y',           true, true),
            array('Y',           true, true),
            array('true',        true, true),
            array('True',        true, true),
            array('TrUe',        true, true),
            array('t',           true, true),
            array('T',           true, true),
            array(1,             true, true),
            array('1',           true, true),
            array('not empty',   true, true),
            array(array(1),      true, true),
            // sanitize to false
            array(false,         true, false),
            array('off',         true, false),
            array('Off',         true, false),
            array('OfF',         true, false),
            array('no',          true, false),
            array('No',          true, false),
            array('NO',          true, false),
            array('n',           true, false),
            array('N',           true, false),
            array('false',       true, false),
            array('False',       true, false),
            array('FaLsE',       true, false),
            array('f',           true, false),
            array('F',           true, false),
            array(0,             true, false),
            array('0',           true, false),
        );
    }
}
