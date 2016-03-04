<?php
namespace Aura\Filter\Rule;

class FakeCharCase extends AbstractCharCase
{
    protected function mbstring()
    {
        return false;
    }

    public function strtolower($str)
    {
        return parent::strtolower($str);
    }

    public function strtoupper($str)
    {
        return parent::strtoupper($str);
    }

    public function ucwords($str)
    {
        return parent::ucwords($str);
    }
}
