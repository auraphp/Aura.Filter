<?php
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Sanitizes a value to a string with only word characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class WordTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('abc _ 123 - ,./', true, 'abc_123'),
        );
    }
}
