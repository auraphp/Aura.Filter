<?php
namespace Aura\Filter\Rule\Sanitize;

class UuidTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            // sanitize passes
            array(
                '12345678-90ab-cDef-1234-567890123456',
                true,
                '12345678-90ab-cDef-1234-567890123456'
            ),
            array(
                '1234567890abcDef1234567890123456',
                true,
                '12345678-90ab-cDef-1234-567890123456'
            ),
            array(
                '12345678-90ab-cDef-1234-5678&&90123456',
                true,
                '12345678-90ab-cDef-1234-567890123456'
            ),
            array(
                '1234567890abcDef12345678&&90123456',
                true,
                '12345678-90ab-cDef-1234-567890123456'
            ),
            array(
                '1234#@5678-90ab-cdef-1234-5678&&90123456',
                true,
                '12345678-90ab-cdef-1234-567890123456'
            ),


            // sanitize fails
            array('', false, ''),
            array(
                '1234*&56789-0ab-cdef-1234-567890123456abc',
                false,
                '1234*&56789-0ab-cdef-1234-567890123456abc'
            ),

        );
    }
}
