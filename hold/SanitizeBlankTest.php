<?php
namespace Aura\Filter\Rule\Sanitize;

class BlankTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return [
            ["",                true, null],
            [" ",               true, null],
            ["\t",              true, null],
            ["\n",              true, null],
            ["\r",              true, null],
            [" \t \n \r ",      true, null],
        ];
    }
}
