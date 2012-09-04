<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

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
            [array()],
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
            [array(), false, array()],
            ['abc _ 123 - ,./', true, 'abc_123'],
        ];
    }
}
