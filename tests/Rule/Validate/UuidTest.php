<?php
namespace Aura\Filter\Rule\Validate;

class UuidTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('12345678-90ab-cdef-1234-567890123456'),
            array('12345678-90Ab-cdef-1234-5678901abc56'),
            array('12345678-90ab-cdef-1234-567890123456'),
            array('11111111-1111-1111-1111-111111111111'),
            array('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array('1000067890abcdef1234562340123456'),
            array('12345678-90ab-cdef-1234-5678901234567'),
            array('123-34324'),
            array('97844444-asdf-fgfd-vf45-383621139112'),
            array('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaaa'),
            array('10000678-90ab-cdef-1234-56240&123456'),
            array('100Ga678-90ab-cdef-1234-562340&123456'),
            array('100Aa678-90ab-cdef-1234-562340&123456'),
            array(''),
        );
    }
}
