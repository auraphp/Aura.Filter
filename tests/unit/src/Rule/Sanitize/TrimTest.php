<?php
namespace Aura\Filter\Rule\Sanitize;

class TrimTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return [
            [array(), false, array()],
            [' abc ', true, 'abc'],
        ];
    }
}
