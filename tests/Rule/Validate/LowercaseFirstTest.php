<?php

namespace Aura\Filter\Rule\Validate;

class LowercaseFirstTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('ab Cd'),
            array('eFGH'),
            array('аБ ВВ'),
            array('фг ег'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array('ABCD'),
            array('Ef gH'),
            array('Аб вВ'),
            array('Фг ег'),
        );
    }
}

