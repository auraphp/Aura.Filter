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
        $this->pdo->query('CREATE TABLE test (val INT, foo VARCHAR(10))');
        for ($i = 1; $i <= 10; $i++) {
            $this->pdo->query("INSERT INTO test (val, foo) VALUES ($i, 'foo{$i}')");
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
        $object->foo = "foo$value";
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
        return [
            [1],
            [2],
            [3],
            [4],
            [5],
            [6],
            [7],
            [8],
            [9],
            [10],
        ];
    }

    public function providerIsNot()
    {
        return [
            [null],
            [false],
            [''],
            [1.2],
            ['a'],
            ['b'],
            ['c'],
            [11],
            [12]
        ];
    }

    public function testIs_where()
    {
        $object = $this->getObject(1);
        $rule = $this->newRule();
        $this->assertTrue($rule->__invoke($object, 'field', 'test', 'val', 'foo = "foo1"'));
    }

    public function testIs_whereBind()
    {
        $object = $this->getObject(1);
        $rule = $this->newRule();
        $this->assertTrue($rule->__invoke($object, 'field', 'test', 'val', 'foo = :foo'));
    }
}
