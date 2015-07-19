<?php

namespace Aura\Filter\Rule\Sanitize;

class AlphaTest extends AbstractSanitizeTest {

    public function providerTo() {
        return array(
            array('^&* abc 123 ,./', true, 'abc'),
            array('^&* abc гдб 123 ,./', true, 'abcгдб'),
            array(file_get_contents('./tests/MbStrings/UTF-16LE/alpha'), true,file_get_contents('./tests/MbStrings/UTF-16LE/alpha_true')),
        );
    }

}
