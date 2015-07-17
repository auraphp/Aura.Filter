<?php
namespace Aura\Filter\Rule\Sanitize;

class AlnumTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            // value, result, expect
            array('$#% abc абв () 123 ,./', true, 'abcабв123'),
        );
    }
}
