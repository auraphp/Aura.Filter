<?php
namespace Aura\Filter\Rule\Validate;

class TrimTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('abc'),
            array('абв'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array(' abc '),
            array(' абв '),
        );
    }
}
