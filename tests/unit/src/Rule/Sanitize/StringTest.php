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
        return [
            ['abc 123 ,./', true, 'abc@123@,./'],
            [12345, true, '12345'],
        ];
    }
}
