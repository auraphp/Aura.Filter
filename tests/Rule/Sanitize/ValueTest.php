<?php
namespace Aura\Filter\Rule\Sanitize;

class ValueTest extends AbstractSanitizeTest
{
    protected $other_value = '1';

    protected function getArgs()
    {
        $args = parent::getArgs();
        $args[] = $this->other_value;
        return $args;
    }

    public function providerTo()
    {
        return array(
            array(0,         true, '1'),
            array(1,         true, '1'),
            array('1',       true, '1'),
            array(true,      true, '1'),
            array(false,     true, '1'),
        );
    }
}
