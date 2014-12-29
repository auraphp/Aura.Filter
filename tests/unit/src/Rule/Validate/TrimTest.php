<?php
namespace Aura\Filter\Rule\Validate;

class TrimTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('abc'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array(' abc '),
        );
    }
}
