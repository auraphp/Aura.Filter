<?php

namespace Aura\Filter\Rule\Validate;

class UppercaseFirstTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('Ab Cd'),
            array('EFGH'),
            array('АБ ВВ'),
            array('Фг ег'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array('aBCD'),
            array('ef GH'),
            array('аб ВВ'),
            array('фГ ЕГ'),
        );
    }
}

