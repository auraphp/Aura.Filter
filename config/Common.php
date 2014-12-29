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
         * Aura\Filter\Rule\Locator\SanitizeLocator
         */
        $di->params['Aura\Filter\Rule\Locator\SanitizeLocator']['factories'] = array(
            'alnum'                 =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Alnum'),
            'alpha'                 =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Alpha'),
            'between'               =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Between'),
            'bool'                  =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Bool'),
            'closure'               =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Closure'),
            'dateTime'              =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\DateTime'),
            'field'                 =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Field'),
            'float'                 =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Float'),
            'int'                   =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Int'),
            'isbn'                  =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Isbn'),
            'max'                   =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Max'),
            'min'                   =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Min'),
            'regex'                 =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Regex'),
            'string'                =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\String'),
            'strlen'                =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Strlen'),
            'strlenBetween'         =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\StrlenBetween'),
            'strlenMax'             =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\StrlenMax'),
            'strlenMin'             =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\StrlenMin'),
            'trim'                  =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Trim'),
            'value'                 =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Value'),
            'word'                  =>  $di->lazyNew('Aura\Filter\Rule\Sanitize\Word'),
        );

        /**
         * Aura\Filter\Rule\Locator\ValidateLocator
         */
        $di->params['Aura\Filter\Rule\Locator\ValidateLocator']['factories'] = array(
            'alnum'                 =>  $di->lazyNew('Aura\Filter\Rule\Validate\Alnum'),
            'alpha'                 =>  $di->lazyNew('Aura\Filter\Rule\Validate\Alpha'),
            'between'               =>  $di->lazyNew('Aura\Filter\Rule\Validate\Between'),
            'blank'                 =>  $di->lazyNew('Aura\Filter\Rule\Validate\Blank'),
            'bool'                  =>  $di->lazyNew('Aura\Filter\Rule\Validate\Bool'),
            'closure'               =>  $di->lazyNew('Aura\Filter\Rule\Validate\Closure'),
            'creditCard'            =>  $di->lazyNew('Aura\Filter\Rule\Validate\CreditCard'),
            'dateTime'              =>  $di->lazyNew('Aura\Filter\Rule\Validate\DateTime'),
            'email'                 =>  $di->lazyNew('Aura\Filter\Rule\Validate\Email'),
            'equalToField'          =>  $di->lazyNew('Aura\Filter\Rule\Validate\EqualToField'),
            'equalToValue'          =>  $di->lazyNew('Aura\Filter\Rule\Validate\EqualToValue'),
            'float'                 =>  $di->lazyNew('Aura\Filter\Rule\Validate\Float'),
            'inKeys'                =>  $di->lazyNew('Aura\Filter\Rule\Validate\InKeys'),
            'int'                   =>  $di->lazyNew('Aura\Filter\Rule\Validate\Int'),
            'inTableColumn'         =>  $di->lazyNew('Aura\Filter\Rule\Validate\InTableColumn'),
            'inValues'              =>  $di->lazyNew('Aura\Filter\Rule\Validate\InValues'),
            'ipv4'                  =>  $di->lazyNew('Aura\Filter\Rule\Validate\Ipv4'),
            'isbn'                  =>  $di->lazyNew('Aura\Filter\Rule\Validate\Isbn'),
            'max'                   =>  $di->lazyNew('Aura\Filter\Rule\Validate\Max'),
            'min'                   =>  $di->lazyNew('Aura\Filter\Rule\Validate\Min'),
            'regex'                 =>  $di->lazyNew('Aura\Filter\Rule\Validate\Regex'),
            'strictEqualToField'    =>  $di->lazyNew('Aura\Filter\Rule\Validate\StrictEqualToField'),
            'strictEqualToValue'    =>  $di->lazyNew('Aura\Filter\Rule\Validate\StrictEqualToValue'),
            'string'                =>  $di->lazyNew('Aura\Filter\Rule\Validate\String'),
            'strlen'                =>  $di->lazyNew('Aura\Filter\Rule\Validate\Strlen'),
            'strlenBetween'         =>  $di->lazyNew('Aura\Filter\Rule\Validate\StrlenBetween'),
            'strlenMax'             =>  $di->lazyNew('Aura\Filter\Rule\Validate\StrlenMax'),
            'strlenMin'             =>  $di->lazyNew('Aura\Filter\Rule\Validate\StrlenMin'),
            'trim'                  =>  $di->lazyNew('Aura\Filter\Rule\Validate\Trim'),
            'upload'                =>  $di->lazyNew('Aura\Filter\Rule\Validate\Upload'),
            'url'                   =>  $di->lazyNew('Aura\Filter\Rule\Validate\Url'),
            'word'                  =>  $di->lazyNew('Aura\Filter\Rule\Validate\Word'),
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
    }

    public function modify(Container $di)
    {
    }
}
