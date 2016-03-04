<?php
namespace Aura\Filter\Rule\Validate;

class LowercaseTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('abcd'),
            array('efgh'),
            array('абвв'),
            array('фгег'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array('aBcd'),
            array('Efgh'),
            array('АБВВ'),
            array('ФГег'),
        );
    }
}
