<?php
namespace Aura\Filter\Rule;

class FakeCharCase extends AbstractCharCase
{
    protected function mbstring(): bool
    {
        return false;
    }

    public function strtolower($str): string
    {
        return parent::strtolower($str);
    }

    public function strtoupper($str): string
    {
        return parent::strtoupper($str);
    }

    public function ucwords($str): string
    {
        return parent::ucwords($str);
    }
}
