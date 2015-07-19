<?php
namespace Aura\Filter\Rule\Sanitize;

class AlnumTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        //print file_get_contents('../../StringsTest/UTF-16LE/Sanitize/alnum');
        return array(
            // value, result, expect
            array('$#% abc () 123 ,./', true, 'abc123'),
            array('$#% abc () 123 влц ,./', true, 'abc123влц'),
            array(file_get_contents('./tests/MbStrings/UTF-16LE/alnum'), true, file_get_contents('./tests/MbStrings/UTF-16LE/alnum_true'))
        );
    }
}
