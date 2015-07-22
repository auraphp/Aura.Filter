<?php
namespace Aura\Filter\Rule\Sanitize;

class AlnumTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            // value, result, expect
            array('$#% abc () 123 ,./', true, 'abc123'),
            array('$#% abc () 123 влц ,./', true, 'abc123влц'),
        );
    }
}
