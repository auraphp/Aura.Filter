<?php
// @codingStandardsIgnoreFile

namespace Aura\Filter\Spec;

class SubSpecFactory
{
    protected $factory;

    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    public function newSubSpec($class = 'Aura\Filter\SubjectFilter'): SubSpec
    {
        $subject = $this->factory->newSubjectFilter($class);
        return new SubSpec($subject);
    }

}
