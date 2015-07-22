<?php
namespace Aura\Filter\Rule\Sanitize;

class AlphaTest extends AbstractSanitizeTest
{
    public function providerTo() {
        return array(
            array('^&* abc 123 ,./', true, 'abc'),
            array('^&* abc гдб 123 ,./', true, 'abcгдб'),
        );
    }

}
