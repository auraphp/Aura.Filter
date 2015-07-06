<?php
namespace Aura\Filter\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        /**
         * Aura\Filter\Spec\SanitizeSpec
         */
        $di->params['Aura\Filter\Spec\SanitizeSpec'] = array(
            'locator' => $di->lazyNew('Aura\Filter\Locator\SanitizeLocator'),
        );

        /**
         * Aura\Filter\Spec\ValidateSpec
         */
        $di->params['Aura\Filter\Spec\ValidateSpec'] = array(
            'locator' => $di->lazyNew('Aura\Filter\Locator\ValidateLocator'),
        );

        /**
         * Aura\Filter\SubjectFilter
         */
        $di->params['Aura\Filter\SubjectFilter'] = array(
            'validate_spec' => $di->lazyNew('Aura\Filter\Spec\ValidateSpec'),
            'sanitize_spec' => $di->lazyNew('Aura\Filter\Spec\SanitizeSpec'),
            'failures' => $di->lazyNew('Aura\Filter\Failure\FailureCollection'),
        );

        /**
         * Aura\Filter\ValueFilter
         */
        $di->params['Aura\Filter\ValueFilter'] = array(
            'validate_locator' => $di->lazyNew('Aura\Filter\Locator\ValidateLocator'),
            'sanitize_locator' => $di->lazyNew('Aura\Filter\Locator\SanitizeLocator'),
        );
    }

    public function modify(Container $di)
    {
    }
}
