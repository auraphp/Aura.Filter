<?php
namespace Aura\Filter\Rule\Validate;

class IsbnTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('3-7814-0334-3'),
            array('3-8053-1903-7'),
            array('960-7037-43-X'),
            array('88-435-6938-4'),
            array('3836211394'),
            array('978-3836211390'),
            array('978-3836211390'),
            array('isbn 88-435-6938-4'),
            array('0201633612'),
            array('978-0201633610'),
            array('80-902734-1-6'),
            array('85-359-0277-5'),
            array('99921-58-10-7'),
            array('960-425-059-0'),
            array('0-8044-2957-X'),
            array('0-9752298-0-X'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array('978-3836211391'),
            array('978-3836211391'),
            array('isbn'),
            array('3836211397'),
        );
    }
}
