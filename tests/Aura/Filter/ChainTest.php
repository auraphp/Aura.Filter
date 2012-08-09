<?php
namespace Aura\Filter;

class ChainTest extends \PHPUnit_Framework_TestCase
{
    protected $chain;
    
    protected function setUp()
    {
        $this->chain = new Chain(new RuleLocator([
            'alnum'                 => function() { return new Rule\Alnum; },
            'alpha'                 => function() { return new Rule\Alpha; },
            'between'               => function() { return new Rule\Between; },
            'blank'                 => function() { return new Rule\Blank; },
            'int'                   => function() { return new Rule\Int; },
            'max'                   => function() { return new Rule\Max; },
            'min'                   => function() { return new Rule\Min; },
            'regex'                 => function() { return new Rule\Regex; },
            'string'                => function() { return new Rule\String; },
            'strlen'                => function() { return new Rule\Strlen; },
        ]));
    }
    
    public function testValue()
    {
        // validate
        $actual = 'abc123def';
        $this->assertTrue($this->chain->value($actual, Value::IS, 'alnum'));
        
        // sanitize in place
        $expect = 123;
        $this->assertTrue($this->chain->value($actual, Value::FIX, 'int'));
        $this->assertSame(123, $actual);
    }
    
    public function testGetRuleLocator()
    {
        $actual = $this->chain->getRuleLocator();
        $expect = 'Aura\Filter\RuleLocator';
        $this->assertInstanceOf($expect, $actual);
    }
    
    public function testAddAndGetRules()
    {
        $this->chain->addSoftRule('field1', Value::IS, 'alnum');
        $this->chain->addHardRule('field1', Value::IS, 'alpha');
        
        $this->chain->addSoftRule('field2', Value::IS, 'alnum');
        $this->chain->addHardRule('field2', Value::IS, 'alpha');
        
        $actual = $this->chain->getRules();
        $expect = [
            0 => [
                'field' => 'field1',
                'method' => 'is',
                'name' => 'alnum',
                'params' => [],
                'type' => Chain::SOFT_RULE,
                'applied' => false,
            ],
            1 => [
                'field' => 'field1',
                'method' => 'is',
                'name' => 'alpha',
                'params' => [],
                'type' => Chain::HARD_RULE,
                'applied' => false,
            ],
            2 => [
                'field' => 'field2',
                'method' => 'is',
                'name' => 'alnum',
                'params' => [],
                'type' => Chain::SOFT_RULE,
                'applied' => false,
            ],
            3 => [
                'field' => 'field2',
                'method' => 'is',
                'name' => 'alpha',
                'params' => [],
                'type' => Chain::HARD_RULE,
                'applied' => false,
            ],
        ];
        
        $this->assertSame($expect, $actual);
    }

    public function testExec()
    {
        $this->chain->addSoftRule('field', Value::IS, 'alnum');
        $this->chain->addHardRule('field', Value::IS, 'alpha');
        
        $data = (object) ['field' => 'foo'];
        $result = $this->chain->exec($data);
        $this->assertTrue($result);
        $messages = $this->chain->getMessages();
        $this->assertTrue(empty($messages));
    }
    
    public function testExec_hardRule()
    {
        $this->chain->addHardRule('field', Value::IS, 'alnum');
        $this->chain->addHardRule('field', Value::IS, 'alpha');
        
        $data = (object) ['field' => array()];
        $result = $this->chain->exec($data);
        $this->assertFalse($result);
        
        $expect = [
            'field' => [
                0 => [
                    'field' => 'field',
                    'method' => 'is',
                    'name' => 'alnum',
                    'params' => [],
                    'message' => 'FILTER_ALNUM',
                    'type' => 'HARD_RULE',
                ],
            ],
        ];

        $actual = $this->chain->getMessages();
        
        $this->assertSame($expect, $actual);
    }

    public function testExec_softRule()
    {
        $this->chain->addSoftRule('field1', Value::IS, 'alnum');
        $this->chain->addHardRule('field1', Value::IS, 'alpha');
        $this->chain->addHardRule('field1', Value::FIX, 'string');
        $this->chain->addHardRule('field2', Value::IS, 'int');
        $this->chain->addHardRule('field2', Value::FIX, 'int');
        
        $data = (object) [
            'field1' => array(),
            'field2' => 88
        ];
        
        $result = $this->chain->exec($data);
        $this->assertFalse($result);
        
        $expect = [
            'field1' => [
                0 => [
                    'field' => 'field1',
                    'method' => 'is',
                    'name' => 'alnum',
                    'params' => [],
                    'message' => 'FILTER_ALNUM',
                    'type' => 'SOFT_RULE',
                ],
                1 => [
                    'field' => 'field1',
                    'method' => 'is',
                    'name' => 'alpha',
                    'params' => [],
                    'message' => 'FILTER_ALPHA',
                    'type' => 'HARD_RULE',
                ],
            ],
        ];

        $actual = $this->chain->getMessages();
        $this->assertSame($expect, $actual);
    }
    
    public function testExec_stopRule()
    {
        $this->chain->addSoftRule('field1', Value::IS, 'alnum');
        $this->chain->addStopRule('field1', Value::IS, 'alpha');
        $this->chain->addHardRule('field2', Value::IS, 'int');
        
        $data = (object) ['field1' => array()];
        $result = $this->chain->exec($data);
        $this->assertFalse($result);
        
        $expect = [
            'field1' => [
                0 => [
                    'field' => 'field1',
                    'method' => 'is',
                    'name' => 'alnum',
                    'params' => [],
                    'message' => 'FILTER_ALNUM',
                    'type' => 'SOFT_RULE',
                ],
                1 => [
                    'field' => 'field1',
                    'method' => 'is',
                    'name' => 'alpha',
                    'params' => [],
                    'message' => 'FILTER_ALPHA',
                    'type' => 'STOP_RULE',
                ],
            ],
        ];

        $actual = $this->chain->getMessages();
        
        $this->assertSame($expect, $actual);
    }
    
    public function testExec_sanitizesInPlace()
    {
        $this->chain->addHardRule('field', Value::FIX, 'string', 'foo', 'bar');
        $data = (object) ['field' => 'foo'];
        $result = $this->chain->exec($data);
        $this->assertTrue($result);
        $this->assertSame($data->field, 'bar');
    }
}
