<?php
namespace Aura\Filter\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        /**
         * Aura\Filter\Rule\Any
         */
        $di->setter['Aura\Filter\Rule\Any']['setRuleLocator'] = $di->lazyNew('Aura\Filter\RuleLocator');

        /**
         * Aura\Filter\Filter
         */
        $di->params['Aura\Filter\Filter'] = [
            'rule_locator' => $di->lazyNew('Aura\Filter\RuleLocator'),
        ];

        /**
         * Aura\Filter\RuleLocator
         */
        $di->params['Aura\Filter\RuleLocator']['factories'] = [
            'alnum'                 =>  $di->lazyNew('Aura\Filter\Rule\Alnum'),
            'alpha'                 =>  $di->lazyNew('Aura\Filter\Rule\Alpha'),
            'any'                   =>  $di->lazyNew('Aura\Filter\Rule\Any'),
            'between'               =>  $di->lazyNew('Aura\Filter\Rule\Between'),
            'blank'                 =>  $di->lazyNew('Aura\Filter\Rule\Blank'),
            'bool'                  =>  $di->lazyNew('Aura\Filter\Rule\Bool'),
            'closure'               =>  $di->lazyNew('Aura\Filter\Rule\Closure'),
            'creditCard'            =>  $di->lazyNew('Aura\Filter\Rule\CreditCard'),
            'dateTime'              =>  $di->lazyNew('Aura\Filter\Rule\DateTime'),
            'email'                 =>  $di->lazyNew('Aura\Filter\Rule\Email'),
            'equalToField'          =>  $di->lazyNew('Aura\Filter\Rule\EqualToField'),
            'equalToValue'          =>  $di->lazyNew('Aura\Filter\Rule\EqualToValue'),
            'float'                 =>  $di->lazyNew('Aura\Filter\Rule\Float'),
            'inKeys'                =>  $di->lazyNew('Aura\Filter\Rule\InKeys'),
            'inTableColumn'         =>  $di->lazyNew('Aura\Filter\Rule\InTableColumn'),
            'inValues'              =>  $di->lazyNew('Aura\Filter\Rule\InValues'),
            'int'                   =>  $di->lazyNew('Aura\Filter\Rule\Int'),
            'ipv4'                  =>  $di->lazyNew('Aura\Filter\Rule\Ipv4'),
            'max'                   =>  $di->lazyNew('Aura\Filter\Rule\Max'),
            'min'                   =>  $di->lazyNew('Aura\Filter\Rule\Min'),
            'regex'                 =>  $di->lazyNew('Aura\Filter\Rule\Regex'),
            'strictEqualToField'    =>  $di->lazyNew('Aura\Filter\Rule\StrictEqualToField'),
            'strictEqualToValue'    =>  $di->lazyNew('Aura\Filter\Rule\StrictEqualToValue'),
            'string'                =>  $di->lazyNew('Aura\Filter\Rule\String'),
            'strlen'                =>  $di->lazyNew('Aura\Filter\Rule\Strlen'),
            'strlenBetween'         =>  $di->lazyNew('Aura\Filter\Rule\StrlenBetween'),
            'strlenMax'             =>  $di->lazyNew('Aura\Filter\Rule\StrlenMax'),
            'strlenMin'             =>  $di->lazyNew('Aura\Filter\Rule\StrlenMin'),
            'trim'                  =>  $di->lazyNew('Aura\Filter\Rule\Trim'),
            'upload'                =>  $di->lazyNew('Aura\Filter\Rule\Upload'),
            'url'                   =>  $di->lazyNew('Aura\Filter\Rule\Url'),
            'word'                  =>  $di->lazyNew('Aura\Filter\Rule\Word'),
            'isbn'                  =>  $di->lazyNew('Aura\Filter\Rule\Isbn'),
        ];
    }

    public function modify(Container $di)
    {
    }
}
