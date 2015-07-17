<?php
namespace Aura\Filter\Rule\Sanitize;

class AlphaTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array('^&* abc абв 123 ,./', true, 'abcабв'),
        );
    }
}
