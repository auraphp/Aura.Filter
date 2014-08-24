<?php
namespace Aura\Filter;

class FilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $filter_factory;

    public function setUp()
    {
        $this->filter_factory = new FilterFactory();
    }

    public function testNewInstance()
    {
        $filter = $this->filter_factory->newInstance();
        $this->assertInstanceOf('Aura\Filter\RuleCollection', $filter);
    }

    public function testGetTranslator()
    {
        $translator = $this->filter_factory->getTranslator();
        $this->assertInstanceOf('Aura\Filter\Translator', $translator);
    }

    public function testSetTranslator()
    {
        $translator = $this->getMock('Aura\Filter\TranslatorInterface');
        $this->filter_factory->setTranslator($translator);
        $this->assertSame($this->filter_factory->getTranslator(), $translator);
    }
}
