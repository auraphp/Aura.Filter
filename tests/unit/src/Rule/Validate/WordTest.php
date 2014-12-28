<?php
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Sanitizes a value to a string with only word characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class WordTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return [
            ['abc'],
            ['def'],
            ['ghi'],
            ['abc_def'],
            ['A1s_2Sd'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            [''],
            ['a!'],
            ['^b'],
            ['%'],
            ['ab-db cd-ef'],
        ];
    }
}
