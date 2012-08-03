<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Sanitizes a value to a string with only word characters.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class WordTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_WORD';
    
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
            [""],
            [''],
            ['a!'],
            ['^b'],
            ['%'],
            ['ab-db cd-ef'],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['abc _ 123 - ,./', 'abc_123'],
        ];
    }
}
