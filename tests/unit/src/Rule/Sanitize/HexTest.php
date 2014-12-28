<?php
namespace Aura\Filter\Rule\Sanitize;

class HexTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            // value, result, expect
            array('$#% abc () 123 ,./', true, 'abc123'),
        );
    }
}
