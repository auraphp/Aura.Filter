<?php
namespace Aura\Filter\Rule\Validate;

class UuidHexonlyTest extends AbstractValidateTest
{
    public function providerIs()
    {
        // random 32-char hex strings
        $data = array();
        for ($i = 1; $i <= 10; $i ++) {
            $data[] = array(md5(mt_rand()));
        }
        return $data;
    }

    public function providerIsNot()
    {
        return array(
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
