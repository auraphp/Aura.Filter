<?php
namespace Aura\Filter;

use Aura\Filter\RuleCollection as Filter;

class RuleCollectionTest extends \PHPUnit_Framework_TestCase
{
    protected $filter;
    
    protected function setUp()
    {
        $this->filter = new Filter(new RuleLocator([
            'alnum'   => function() { return new Rule\Alnum; },
            'alpha'   => function() { return new Rule\Alpha; },
            'between' => function() { return new Rule\Between; },
            'blank'   => function() { return new Rule\Blank; },
            'int'     => function() { return new Rule\Int; },
            'max'     => function() { return new Rule\Max; },
            'min'     => function() { return new Rule\Min; },
            'regex'   => function() { return new Rule\Regex; },
            'string'  => function() { return new Rule\String; },
            'strlen'  => function() { return new Rule\Strlen; },
        ]));
    }
    
    public function testValue()
    {
        // validate
        $actual = 'abc123def';
        $this->assertTrue($this->filter->value($actual, Filter::VALUE_IS, 'alnum'));
        
        // sanitize in place
        $expect = 123;
        $this->assertTrue($this->filter->value($actual, Filter::VALUE_FIX, 'int'));
        $this->assertSame(123, $actual);
    }
    
    public function testGetRuleLocator()
    {
        $filter = $this->filter;
        $actual = $this->filter->getRuleLocator();
        $expect = 'Aura\Filter\RuleLocator';
        $this->assertInstanceOf($expect, $actual);
    }
    
    public function testAddAndGetRules()
    {
        $this->filter->addSoftRule('field1', Filter::VALUE_IS, 'alnum');
        $this->filter->addHardRule('field1', Filter::VALUE_IS, 'alpha');
        
        $this->filter->addSoftRule('field2', Filter::VALUE_IS, 'alnum');
        $this->filter->addHardRule('field2', Filter::VALUE_IS, 'alpha');
        
        $actual = $this->filter->getRules();
        $expect = [
            0 => [
                'field' => 'field1',
                'method' => 'is',
                'name' => 'alnum',
                'params' => [],
                'type' => Filter::RULE_SOFT,
                'applied' => false,
            ],
            1 => [
                'field' => 'field1',
                'method' => 'is',
                'name' => 'alpha',
                'params' => [],
                'type' => Filter::RULE_HARD,
                'applied' => false,
            ],
            2 => [
                'field' => 'field2',
                'method' => 'is',
                'name' => 'alnum',
                'params' => [],
                'type' => Filter::RULE_SOFT,
                'applied' => false,
            ],
            3 => [
                'field' => 'field2',
                'method' => 'is',
                'name' => 'alpha',
                'params' => [],
                'type' => Filter::RULE_HARD,
                'applied' => false,
            ],
        ];
        
        $this->assertSame($expect, $actual);
    }

    public function testExec()
    {
        $this->filter->addSoftRule('field', Filter::VALUE_IS, 'alnum');
        $this->filter->addHardRule('field', Filter::VALUE_IS, 'alpha');
        
        $data = (object) ['field' => 'foo'];
        $result = $this->filter->object($data);
        $this->assertTrue($result);
        $messages = $this->filter->getMessages();
        $this->assertTrue(empty($messages));
    }
    
    public function testExec_hardRule()
    {
        $this->filter->addHardRule('field', Filter::VALUE_IS, 'alnum');
        $this->filter->addHardRule('field', Filter::VALUE_IS, 'alpha');
        
        $data = (object) ['field' => array()];
        $result = $this->filter->object($data);
        $this->assertFalse($result);
        
        $expect = [
            'field' => [
                0 => [
                    'field' => 'field',
                    'method' => 'is',
                    'name' => 'alnum',
                    'params' => [],
                    'message' => 'FILTER_ALNUM',
                    'type' => 'RULE_HARD',
                ],
            ],
        ];

        $actual = $this->filter->getMessages();
        
        $this->assertSame($expect, $actual);
    }

    public function testExec_softRule()
    {
        $this->filter->addSoftRule('field1', Filter::VALUE_IS, 'alnum');
        $this->filter->addHardRule('field1', Filter::VALUE_IS, 'alpha');
        $this->filter->addHardRule('field1', Filter::VALUE_FIX, 'string');
        $this->filter->addHardRule('field2', Filter::VALUE_IS, 'int');
        $this->filter->addHardRule('field2', Filter::VALUE_FIX, 'int');
        
        $data = (object) [
            'field1' => array(),
            'field2' => 88
        ];
        
        $result = $this->filter->object($data);
        $this->assertFalse($result);
        
        $expect = [
            'field1' => [
                0 => [
                    'field' => 'field1',
                    'method' => 'is',
                    'name' => 'alnum',
                    'params' => [],
                    'message' => 'FILTER_ALNUM',
                    'type' => 'RULE_SOFT',
                ],
                1 => [
                    'field' => 'field1',
                    'method' => 'is',
                    'name' => 'alpha',
                    'params' => [],
                    'message' => 'FILTER_ALPHA',
                    'type' => 'RULE_HARD',
                ],
            ],
        ];

        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }
    
    public function testExec_stopRule()
    {
        $this->filter->addSoftRule('field1', Filter::VALUE_IS, 'alnum');
        $this->filter->addStopRule('field1', Filter::VALUE_IS, 'alpha');
        $this->filter->addHardRule('field2', Filter::VALUE_IS, 'int');
        
        $data = (object) ['field1' => array()];
        $result = $this->filter->object($data);
        $this->assertFalse($result);
        
        $expect = [
            'field1' => [
                0 => [
                    'field' => 'field1',
                    'method' => 'is',
                    'name' => 'alnum',
                    'params' => [],
                    'message' => 'FILTER_ALNUM',
                    'type' => 'RULE_SOFT',
                ],
                1 => [
                    'field' => 'field1',
                    'method' => 'is',
                    'name' => 'alpha',
                    'params' => [],
                    'message' => 'FILTER_ALPHA',
                    'type' => 'RULE_STOP',
                ],
            ],
        ];

        $actual = $this->filter->getMessages();
        
        $this->assertSame($expect, $actual);
    }
    
    public function testExec_sanitizesInPlace()
    {
        $this->filter->addHardRule('field', Filter::VALUE_FIX, 'string', 'foo', 'bar');
        $data = (object) ['field' => 'foo'];
        $result = $this->filter->object($data);
        $this->assertTrue($result);
        $this->assertSame($data->field, 'bar');
    }
}
