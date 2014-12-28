<?php
namespace Aura\Filter\Rule\Sanitize;

class AlnumTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return [
            // value, result, expect
            ['$#% abc () 123 ,./', true, 'abc123'],
        ];
    }
}
