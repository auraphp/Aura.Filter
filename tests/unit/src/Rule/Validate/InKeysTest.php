<?php
namespace Aura\Filter\Rule\Validate;

class InKeysTest extends AbstractValidateTest
{
    protected $opts = array(
        0      => 'val0',
        1      => 'val1',
        'key0' => 'val3',
        'key1' => 'val4',
        'key2' => 'val5',
    );

    protected function getArgs()
    {
        $args = parent::getArgs();
        $args[] = $this->opts;
        return $args;
    }

    public function providerIs()
    {
        return array(
            array(0),
            array(1),
            array('key0'),
            array('key1'),
            array('key2'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(null),
            array(false),
            array(''),
            array(1.2),
            array(3),
            array(4),
            array('a'),
            array('b'),
            array('c'),
        );
    }
}
