<?php
namespace Aura\Filter;

class FilterContainerTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $filter_container = new FilterContainer();
        $validate_locator = $filter_container->getValidateLocator();
        $this->markTestIncomplete();
    }
}
