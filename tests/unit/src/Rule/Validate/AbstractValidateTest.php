<?php
namespace Aura\Filter\Rule\Validate;

abstract class AbstractValidateTest extends \PHPUnit_Framework_TestCase
{
    protected function getClass()
    {
        return substr(get_class($this), 0, -4);
    }

    protected function newRule()
    {
        $class = $this->getClass();
        $rule = new $class();
        return $rule;
    }

    protected function getArgs()
    {
        return array();
    }

    protected function getObject($value)
    {
        return (object) array('field' => $value);
    }

    protected function invoke($value)
    {
        $args = array_merge(
            array(
                $this->getObject($value),
                'field'
            ),
            (array) $this->getArgs()
        );
        $rule = $this->newRule();
        return call_user_func_array($rule, $args);
    }

    /**
     * @dataProvider providerIs
     */
    public function testIs($value)
    {
        $this->assertTrue($this->invoke($value));
    }

    /**
     * @dataProvider providerIsNot
     */
    public function testIsNot($value)
    {
        $this->assertFalse($this->invoke($value));
    }

    // /**
    //  * @dataProvider providerIsBlankOr
    //  */
    // public function testIsBlankOr($value)
    // {
    //     list($data, $field) = $this->getPrep($value);
    //     $rule = $this->newRule($data, $field);
    //     $this->assertTrue($this->ruleIsBlankOr($rule));
    // }

    // /**
    //  * @dataProvider providerFix
    //  */
    // public function testFix($value, $result, $expect)
    // {
    //     list($data, $field) = $this->getPrep($value);
    //     $rule = $this->newRule($data, $field);
    //     $this->assertSame($result, $this->ruleFix($rule));
    //     $actual = $rule->getValue();
    //     $this->assertSame($expect, $actual);
    // }

    // /**
    //  * @dataProvider providerFixBlankOr
    //  */
    // public function testFixBlankOr($value, $result, $expect)
    // {
    //     list($data, $field) = $this->getPrep($value);
    //     $rule = $this->newRule($data, $field);
    //     $this->assertSame($result, $this->ruleFixBlankOr($rule));
    //     $actual = $rule->getValue();
    //     $this->assertSame($expect, $actual);
    // }

    abstract public function providerIs();

    abstract public function providerIsNot();

    public function providerIsBlankOr()
    {
        return array_merge(
            $this->providerIs(),
            [
                [null],
                [''],
                ["\r \t \n"],
            ]
        );
    }

    abstract public function providerFix();

    public function providerFixBlankOr()
    {
        return array_merge(
            $this->providerFix(),
            [
                [null, true, null],
                ['', true, null],
                ["\r \t \n", true, null],
            ]
        );
    }
}
