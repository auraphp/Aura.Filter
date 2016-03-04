<?php
namespace Aura\Filter\Rule\Sanitize;

class LowercaseFirstTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('', true, ''),
            array('A', true, 'a'),
            array('AbCd', true, 'abCd'),
            array('ABCDEF', true, 'aBCDEF'),
            array('Ж', true, 'ж'),
            array('АБВГ', true, 'аБВГ'),
            array('АБВГДЕ', true, 'аБВГДЕ'),
        );
    }
}
