<?php
namespace Aura\Filter\Rule\Validate;

class TrimTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return [
            ['abc'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            [' abc '],
        ];
    }
}
