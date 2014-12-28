<?php
namespace Aura\Filter\Rule\Sanitize;

class IntTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return [
            [array(), false, array()], // cannot sanitize
            ['abc ... 123.45 ,.../', true, 12345],
            ['a-bc .1. alkasldjf 23 aslk.45 ,.../', true, -12345],
            ['1E5', true, 100000],
        ];
    }
}
