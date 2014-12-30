<?php
namespace Aura\Filter\Rule\Validate;

use PDO;

class InTableColumnTest extends AbstractValidateTest
{
    protected $pdo;

    protected function setUp()
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->query('CREATE TABLE test (val INT, bar VARCHAR(10))');
        for ($i = 1; $i <= 10; $i++) {
            $this->pdo->query("INSERT INTO test (val, bar) VALUES ($i, 'bar{$i}')");
        }
    }

    protected function newRule()
    {
        $class = $this->getClass();
        $rule = new $class($this->pdo, '"', '"');
        return $rule;
    }

    public function getObject($value)
    {
        $object = parent::getObject($value);
        $object->bar = "bar$value";
        return $object;
    }

    public function getArgs()
    {
        $args = parent::getArgs();
        $args[] = 'test';
        $args[] = 'val';
        return $args;
    }

    public function providerIs()
    {
        return array(
            array(1),
            array(2),
            array(3),
            array(4),
            array(5),
            array(6),
            array(7),
            array(8),
            array(9),
            array(10),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(null),
            array(false),
            array(''),
            array(1.2),
            array('a'),
            array('b'),
            array('c'),
            array(11),
            array(12),
        );
    }

    public function testIs_where()
    {
        $object = $this->getObject(1);
        $rule = $this->newRule();
        $this->assertTrue($rule->__invoke($object, 'foo', 'test', 'val', 'bar = "bar1"'));
    }

    public function testIs_whereBind()
    {
        $object = $this->getObject(1);
        $rule = $this->newRule();
        $this->assertTrue($rule->__invoke($object, 'foo', 'test', 'val', 'bar = :bar'));
    }
}
