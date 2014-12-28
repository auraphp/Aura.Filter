<?php
namespace Aura\Filter\Rule\Sanitize;

class IsbnTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return [
            // sanitize to true
            ['3836211394',      true, '3836211394'],
            ['3-7814-0334-3',   true, '3781403343'],
            ['960-7037-43-X',   true, '960703743X'],

            // sanitize to false
            ['isbn',            false, 'isbn'],
            ['960-7037-43-x',   false, '960-7037-43-x'],
        ];
    }
}
