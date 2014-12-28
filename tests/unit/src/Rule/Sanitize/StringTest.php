<?php
namespace Aura\Filter\Rule\Sanitize;

class StringTest extends AbstractSanitizeTest
{
    protected $find = ' ';
    protected $repl = '@';

    protected function getArgs()
    {
        return array($this->find, $this->repl);
    }

    public function providerTo()
    {
        return array(
            array('abc 123 ,./', true, 'abc@123@,./'),
            array(12345, true, '12345'),
        );
    }
}
