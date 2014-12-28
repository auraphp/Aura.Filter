<?php
namespace Aura\Filter\Rule\Sanitize;

class EqualToValueTest extends AbstractSanitizeTest
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
        return [
            [0,         true, '1'],
            [1,         true, '1'],
            ['1',       true, '1'],
            [true,      true, '1'],
            [false,     true, '1'],
        ];
    }
}
