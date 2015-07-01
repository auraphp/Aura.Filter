<?php
namespace Aura\Filter\Rule\Validate;

class Ipv6Test extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
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
