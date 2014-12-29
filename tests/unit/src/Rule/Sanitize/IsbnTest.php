<?php
namespace Aura\Filter\Rule\Sanitize;

class IsbnTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            // sanitize to true
            array('3836211394',      true, '3836211394'),
            array('3-7814-0334-3',   true, '3781403343'),
            array('960-7037-43-X',   true, '960703743X'),

            // sanitize to false
            array('isbn',            false, 'isbn'),
            array('960-7037-43-x',   false, '960-7037-43-x'),
        );
    }
}
