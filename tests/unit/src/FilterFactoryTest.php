<?php
namespace Aura\Filter;

use Aura\Filter\Rule\Validate\InTableColumn;
use PDO;

class FilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testWithPdo()
    {
        $pdo = new PDO('sqlite::memory:');
        $filter_factory = new FilterFactory($pdo);
        $validate_locator = $filter_factory->getValidateLocator();
        $this->assertInstanceOf(
            InTableColumn::CLASS,
            $validate_locator->get('inTableColumn')
        );
    }
}
