<?php
namespace Aura\Filter\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        /**
         * Aura\Filter\Filter
         */
        $di->params['Aura\Filter\Filter'] = array(
            'validate_spec' => $di->lazyNew('Aura\Filter\Spec\ValidateSpec'),
            'sanitize_spec' => $di->lazyNew('Aura\Filter\Spec\SanitizeSpec'),
        );
        /**
         * Aura\Filter\Spec\SanitizeSpec
         */
        $di->params['Aura\Filter\Spec\SanitizeSpec'] = array(
            'rule_locator' => $di->lazyNew('Aura\Filter\Rule\Locator\SanitizeLocator'),
        );

        /**
         * Aura\Filter\Spec\ValidateSpec
         */
        $di->params['Aura\Filter\Spec\ValidateSpec'] = array(
            'rule_locator' => $di->lazyNew('Aura\Filter\Rule\Locator\ValidateLocator'),
        );

        /**
         * Aura\Filter\ValueFilter
         */
        $di->params['Aura\Filter\ValueFilter'] = array(
            'validate_locator' => $di->lazyNew('Aura\Filter\Rule\Locator\ValidateLocator'),
            'sanitize_locator' => $di->lazyNew('Aura\Filter\Rule\Locator\SanitizeLocator'),
        );

    }

    public function modify(Container $di)
    {
    }
}
