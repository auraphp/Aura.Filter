<?php

namespace Aura\Filter\Rule\Validate;

class TitlecaseTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('Ab Cd'),
            array('Efgh'),
            array('Аб Вв'),
            array('Фг Ег'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array('aBcd'),
            array('Ef gH'),
            array('АБ ВВ'),
            array('ФГ ег'),
        );
    }
}

