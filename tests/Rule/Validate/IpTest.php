<?php
namespace Aura\Filter\Rule\Validate;

class IpTest extends AbstractValidateTest
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
            array('2001:cdba:0000:0000:0000:0000:3257:9652'),
            array('2001:cdba:0:0:0:0:3257:9652'),
            array('2001:cdba::3257:9652'),
            array('2001:0:0:0:0:0:0:3F'),
            array('2001::3F'),
            array('1200:0000:AB00:1234:0000:2552:7777:1313'),
            array('21DA:D3:0:2F3B:2AA:FF:FE28:9C5A'),
            array('2001:db8:0:0:0:FFFF:192.168.0.5'),
            array('2001:db8::FFFF:192.168.0.5'),
            array('0:0:0:0:0:0:0:0'),
            array('::'),
            array('0:0:0:0:0:0:0:1'),
            array('::1'),
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
            array('1200::AB00:1234::2552:7777:1313'),
            array('1200:0000:AB00:1234:O000:2552:7777:1313'),
            array('0:0:0:0:0:0:0:0:'),
            array('0:0:0:0:0:0:0:0:0'),
            array('0:'),
            array('0:0:'),
            array('0:0:0:'),
            array('0:0:0:0:'),
            array('0:0:0:0:0:'),
            array('0:0:0:0:0:0:'),
            array('0:0:0:0:0:0:0:'),
            array('G:0:0:0:0:0:0:0'),
            array('0:G:0:0:0:0:0:0'),
            array('0:0:G:0:0:0:0:0'),
            array('0:0:0:G:0:0:0:0'),
            array('0:0:0:0:G:0:0:0'),
            array('0:0:0:0:0:G:0:0'),
            array('0:0:0:0:0:0:G:0'),
            array('0:0:0:0:0:0:0:G'),
        );
    }
}
