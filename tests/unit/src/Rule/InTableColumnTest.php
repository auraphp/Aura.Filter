<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

use PDO;

class InTableColumnTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_IN_TABLE_COLUMN';

    protected $pdo;

    protected function setUp()
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->query('CREATE TABLE test (val INT)');
        for ($i = 1; $i <= 10; $i++) {
            $this->pdo->query("INSERT INTO test (val) VALUES ($i)");
        }
    }

    protected function newRule($data, $field)
    {
        $class = $this->getClass();
        $rule = new $class($this->pdo, '"', '"');
        $rule->prep((object) $data, $field);
        return $rule;
    }

    public function ruleIs($rule)
    {
        return $rule->is('test', 'val');
    }

    public function ruleIsNot($rule)
    {
        return $rule->isNot('test', 'val');
    }

    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr('test', 'val');
    }

    public function ruleFix($rule)
    {
        return $rule->fix('test', 'val');
    }

    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr('test', 'val');
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

    public function providerFix()
    {
        return [
            ['no-good', false, 'no-good'], // cannot fix
        ];
    }
}
