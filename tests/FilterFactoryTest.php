<?php
namespace Aura\Filter;

class FilterContainerTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $filter_factory = new FilterContainer();
        $validate_locator = $filter_factory->getValidateLocator();
        $this->markTestIncomplete();
    }
}
