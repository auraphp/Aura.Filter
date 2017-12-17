<?php
namespace Aura\Filter\Rule;

class Custom
{
    public function __invoke($subject, $field)
    {
        // done!

        return true;
    }
}
