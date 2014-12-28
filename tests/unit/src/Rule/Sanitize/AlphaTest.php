<?php
namespace Aura\Filter\Rule\Sanitize;

class AlphaTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return [
            ['^&* abc 123 ,./', true, 'abc'],
        ];
    }
}
