<?php
namespace Aura\Filter\Rule\Sanitize;

class TrimTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array(' abc ', true, 'abc'),
            array(' абв ', true, 'абв'),
        );
    }
}
