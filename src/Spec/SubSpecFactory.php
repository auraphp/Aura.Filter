<?php
declare(strict_types=1);

// @codingStandardsIgnoreFile

namespace Aura\Filter\Spec;

use Aura\Filter\SubjectFilter;
class SubSpecFactory
{
    protected $factory;

    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    public function newSubSpec($class = SubjectFilter::class): SubSpec
    {
        $subject = $this->factory->newSubjectFilter($class);
        return new SubSpec($subject);
    }

}
