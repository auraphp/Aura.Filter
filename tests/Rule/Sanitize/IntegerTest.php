<?php
namespace Aura\Filter\Rule\Sanitize;

class IntegerTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()), // cannot sanitize
            array('abc ... 123.45 ,.../', true, 12345),
            array('a-bc .1. alkasldjf 23 aslk.45 ,.../', true, -12345),
            array('1E5', true, 100000),
        );
    }
}
