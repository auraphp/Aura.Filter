<?php
namespace Aura\Filter\Rule\Sanitize;

class DoubleTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()), // can't fix
            array(123.45, true, 123.45),
            array('abc ... 123.45 ,.../', true, 123.450),
            array('a-bc .1. alkasldjf 23 aslk.45 ,.../', true, -.123450),
            array('1E5', true, 100000.0),
        );
    }
}
