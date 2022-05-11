<?php
namespace Aura\Filter\Spec;

use Aura\Filter\FilterFactory;
use Aura\Filter\SubjectFilter;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class SubspecTest extends TestCase
{
    /** @var SubjectFilter */
    protected $filter;

    protected function set_up()
    {
        $filter_factory = new FilterFactory();
        $this->filter = $filter_factory->newSubjectFilter();
    }

    public function testMultidimensionalSupport()
    {
        $data = [
            'id' => 'asd',
            'user' => [
                'name' => 'Foo',
                'age' => 'asd',
            ],
            // 'url' => 'http://example.com'
        ];

        $this->filter->validate('id')->is('int');
        $this->filter->validate('url')->is('url');

        $user_spec = $this->filter->subFilter('user'); // add a "SubSpec"
        $user_filter = $user_spec->filter();  // Get the "SubSpec" SubjectFilter

        $user_filter->validate('given-name')->isNotBlank();
        $user_filter->validate('age')->is('int');
        $user_filter->validate('gender')->is('strlen', 1);

        $subject = (object) $data;
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);
        $expect = [
            'id' => [
                'id should have validated as int'
            ],
            'url' => [
                'url should have validated as url',
            ],
            'user' => [
                'given-name' => [
                    'given-name should not have been blank',
                ],
                'age' => [
                    'age should have validated as int'
                ],
                'gender' => [
                    'gender should have validated as strlen(1)'
                ]
            ]
        ];
        $actual = $this->filter->getFailures()->getMessages();
        $this->assertSame($expect, $actual);
    }
}