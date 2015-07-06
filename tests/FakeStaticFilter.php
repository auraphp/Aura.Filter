<?php
namespace Aura\Filter;

class FakeStaticFilter extends AbstractStaticFilter
{
    public static function reset()
    {
        static::$instance = null;
    }
}
