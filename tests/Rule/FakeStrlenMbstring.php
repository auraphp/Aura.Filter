<?php
namespace Aura\Filter\Rule;

class FakeStrlenMbstring extends AbstractStrlen
{
    protected function iconv(): bool
    {
        return false;
    }

    public function strlen($str): int
    {
        return parent::strlen($str);
    }

    public function substr($str, $start, $length = null): string
    {
        return parent::substr($str, $start, $length);
    }

    public function strpad($input, $length, $pad_str = ' ', $type = STR_PAD_RIGHT)
    {
        return parent::strpad($input, $length, $pad_str, $type);
    }
}
