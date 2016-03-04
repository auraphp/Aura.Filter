<?php
namespace Aura\Filter\Rule\Sanitize;

class TitlecaseTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('A', true, 'A'),
            array('Ab cd', true, 'Ab Cd'),
            array('ABC DEF', true, 'Abc Def'),
            array('Ж', true, 'Ж'),
            array('АБ ВГ', true, 'Аб Вг'),
            array('АбвГ ДЕ', true, 'Абвг Де'),
        );
    }
}
