<?php
namespace Aura\Filter\Rule;

abstract class AbstractRuleTest extends \PHPUnit_Framework_TestCase
{
    protected $expect_message;
    
    protected function newRule($data, $field)
    {
        $class = substr(get_class($this), 0, -4);
        $rule = new $class();
        $rule->prep((object) $data, $field);
        return $rule;
    }
    
    public function testGetMessage()
    {
        $rule = $this->newRule(['foo' => 'bar'], 'foo');
        $actual = $rule->getMessage();
        $this->assertSame($this->expect_message, $actual);
    }
    
    public function testGetAndSetValue()
    {
        $data = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ];
        
        // get the field
        $rule = $this->newRule($data, 'foo');
        $expect = 'bar';
        $actual = $rule->getValue();
        $this->assertSame($expect, $actual);
        
        // set the field
        $rule = $this->newRule($data, 'foo');
        $expect = 'doom';
        $rule->setValue($expect);
        $actual = $rule->getValue();
        $this->assertSame($expect, $actual);
        
        // get a nonexistent field
        $rule = $this->newRule($data, 'no_such_field');
        $actual = $rule->getValue();
        $this->assertNull($actual);
    }
    
    
    /**
     * @dataProvider providerIs
     */
    public function testIs($value)
    {
        list($data, $field) = $this->prepForValidate($value);
        $rule = $this->newRule($data, $field);
        $this->assertTrue($this->ruleIs($rule));
    }
    
    /**
     * @dataProvider providerIsNot
     */
    public function testIsNot($value)
    {
        list($data, $field) = $this->prepForValidate($value);
        $rule = $this->newRule($data, $field);
        $this->assertTrue($this->ruleIsNot($rule));
    }
    
    /**
     * @dataProvider providerIsBlankOr
     */
    public function testIsBlankOr($value)
    {
        list($data, $field) = $this->prepForValidate($value);
        $rule = $this->newRule($data, $field);
        $this->assertTrue($this->ruleIsBlankOr($rule));
    }
    
    /**
     * @dataProvider providerFix
     */
    public function testFix($value, $expect)
    {
        list($data, $field) = $this->prepForSanitize($value);
        $rule = $this->newRule($data, $field);
        $this->ruleFix($rule);
        $actual = $rule->getValue();
        $this->assertSame($expect, $actual);
    }
    
    /**
     * @dataProvider providerFixBlankOr
     */
    public function testFixBlankOr($value, $expect)
    {
        list($data, $field) = $this->prepForSanitize($value);
        $rule = $this->newRule($data, $field);
        $this->ruleFixBlankOr($rule);
        $actual = $rule->getValue();
        $this->assertSame($expect, $actual);
    }
    
    // DATA/FIELD FOR PREP ===================================================
    
    public function prepForValidate($value)
    {
        $data  = ['field' => $value];
        $field = 'field';
        return [$data, $field];
    }
    
    public function prepForSanitize($value)
    {
        $data  = ['field' => $value];
        $field = 'field';
        return [$data, $field];
    }
    
    // RULE INVOCATIONS ======================================================
    
    public function ruleIs($rule)
    {
        return $rule->is();
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr();
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot();
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix();
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr();
    }
    
    // PROVIDERS =============================================================
    
    abstract public function providerIs();
    
    abstract public function providerIsNot();
    
    public function providerIsBlankOr()
    {
        return array_merge($this->providerIs(), [
            [null],
            [''],
            ["\r \t \n"],
        ]);
    }
    
    abstract public function providerFix();
    
    public function providerFixBlankOr()
    {
        return array_merge($this->providerFix(), [
            [null, null],
            ['', null],
            ["\r \t \n", null],
        ]);
    }
}
