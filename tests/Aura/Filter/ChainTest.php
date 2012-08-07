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
            'max'                   => function() { return new Rule\Max; },
            'min'                   => function() { return new Rule\Min; },
            'regex'                 => function() { return new Rule\Regex; },
            'string'                => function() { return new Rule\String; },
            'strlen'                => function() { return new Rule\Strlen; },
        ]));
    }
    
    public function testAddAndGetRules()
    {
        $this->chain->addContinue('field1', Value::IS, 'alnum');
        $this->chain->add('field1', Value::IS, 'alpha');
        
        $this->chain->addContinue('field2', Value::IS, 'alnum');
        $this->chain->add('field2', Value::IS, 'alpha');
        
        $actual = $this->chain->getRules();
        $expect = [
            0 => [
                'field' => 'field1',
                'method' => 'is',
                'name' => 'alnum',
                'params' => [],
                'failstop' => false,
            ],
            1 => [
                'field' => 'field1',
                'method' => 'is',
                'name' => 'alpha',
                'params' => [],
                'failstop' => true,
            ],
            2 => [
                'field' => 'field2',
                'method' => 'is',
                'name' => 'alnum',
                'params' => [],
                'failstop' => false,
            ],
            3 => [
                'field' => 'field2',
                'method' => 'is',
                'name' => 'alpha',
                'params' => [],
                'failstop' => true,
            ],
        ];
        
        $this->assertSame($expect, $actual);
    }

    public function testExec_notObject()
    {
        $this->chain->addContinue('field', Value::IS, 'alnum');
        $this->chain->add('field', Value::IS, 'alpha');
        
        $data = ['field' => 'foo'];
        $this->setExpectedException('\InvalidArgumentException');
        $result = $this->chain->exec($data);
    }
    
    public function testExec_success()
    {
        $this->chain->addContinue('field', Value::IS, 'alnum');
        $this->chain->add('field', Value::IS, 'alpha');
        
        $data = (object) ['field' => 'foo'];
        $result = $this->chain->exec($data);
        $this->assertTrue($result);
    }
    
    public function testExec_failAndStop()
    {
        $this->chain->add('field', Value::IS, 'alnum');
        $this->chain->add('field', Value::IS, 'alpha');
        
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
                ],
            ],
        ];

        $actual = $this->chain->getMessages();
        
        $this->assertSame($expect, $actual);
    }

    public function testExec_failAndContinue()
    {
        $this->chain->addContinue('field', Value::IS, 'alnum');
        $this->chain->add('field', Value::IS, 'alpha');
        $this->chain->add('field', Value::IS, 'int');
        
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
                ],
                1 => [
                    'field' => 'field',
                    'method' => 'is',
                    'name' => 'alpha',
                    'params' => [],
                    'message' => 'FILTER_ALPHA',
                ],
            ],
        ];

        $actual = $this->chain->getMessages();
        
        $this->assertSame($expect, $actual);
    }
    
    public function exec_sanitizesInPlace()
    {
        $this->chain->add('field', Value::FIX, 'string', 'foo', 'bar');
        $data = (object) ['field' => 'foo'];
        $result = $this->chain->exec($data);
        $this->assertTrue($result);
        $this->assertSame($data->field, 'bar');
    }
}
