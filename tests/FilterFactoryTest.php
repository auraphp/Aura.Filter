<?php
namespace Aura\Filter;

class FilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $filter_factory = new FilterFactory();
        $validate_locator = $filter_factory->getValidateLocator();
        $this->markTestIncomplete();
    }
}
