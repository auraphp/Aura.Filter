<?php
namespace Aura\Filter\Rule\Sanitize;

class ValueIfBlankTest extends AbstractSanitizeTest
{
    public function getArgs()
    {
        return array('');
    }

    public function providerTo()
    {
        return array(
            array(null,                     true, ''),
            array("",                       true, ''),
            array(" ",                      true, ''),
            array("\t",                     true, ''),
            array("\n",                     true, ''),
            array("\r",                     true, ''),
            array(" \t \n \r ",             true, ''),
            array(0,                        true, 0),
            array(1,                        true, 1),
            array('0',                      true, '0'),
            array('1',                      true, '1'),
            array("Seven 8 nine",           true, "Seven 8 nine"),
            array("non:alpha-numeric's",    true, "non:alpha-numeric's"),
            array('someThing8else',         true, 'someThing8else'),
        );
    }

    public function testIs_withClosure()
    {
        $closure = function ($object, $field) {
            $object->$field = 'foo';
            return true;
        };

        $class = $this->getClass();
        $rule = new $class;

        $object = (object) array();
        $this->assertTrue($rule->__invoke($object, 'field', $closure));
        $this->assertSame('foo', $object->field);
    }
}
