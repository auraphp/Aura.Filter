<?php
namespace Aura\Filter\Rule\Sanitize;

class UppercaseFirstTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('', true, ''),
            array('a', true, 'A'),
            array('Ab cd', true, 'Ab cd'),
            array('ABC DEF', true, 'ABC DEF'),
            array('ж', true, 'Ж'),
            array('аб вг', true, 'Аб вг'),
            array('АбвГ ДЕ', true, 'АбвГ ДЕ'),
        );
    }
}
