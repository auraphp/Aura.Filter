<?php
namespace Aura\Filter\Rule\Validate;

class Ipv4Test extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('141.225.185.101'),
            array('255.0.0.0'),
            array('0.255.0.0'),
            array('0.0.255.0'),
            array('0.0.0.255'),
            array('127.0.0.1'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(' '),
            array(''),
            array('127.0.0.1234'),
            array('127.0.0.0.1'),
            array('256.0.0.0'),
            array('0.256.0.0'),
            array('0.0.256.0'),
            array('0.0.0.256'),
            array('1.'),
            array('1.2.'),
            array('1.2.3.'),
            array('1.2.3.4.'),
            array('a.b.c.d'),
        );
    }
}
