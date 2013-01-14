<?php
/**
 * Package prefix for autoloader.
 */
$loader->add('Aura\Filter\\', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');

/**
 * Load rules
 */
$di->params['Aura\Filter\RuleLocator']['registry'] = [
    'alnum'                 => function() { return new \Aura\Filter\Rule\Alnum; },
    'alpha'                 => function() { return new \Aura\Filter\Rule\Alpha; },
    'any'                   => function() use ($di) {
        $rule = new \Aura\Filter\Rule\Any;
        $rule->setRuleLocator($di->newInstance('\Aura\Filter\Rule\Locator'));
    },
    'between'               => function() { return new \Aura\Filter\Rule\Between; },
    'blank'                 => function() { return new \Aura\Filter\Rule\Blank; },
    'bool'                  => function() { return new \Aura\Filter\Rule\Bool; },
    'creditCard'            => function() { return new \Aura\Filter\Rule\CreditCard; },
    'dateTime'              => function() { return new \Aura\Filter\Rule\DateTime; },
    'email'                 => function() { return new \Aura\Filter\Rule\Email; },
    'equalToField'          => function() { return new \Aura\Filter\Rule\EqualToField; },
    'equalToValue'          => function() { return new \Aura\Filter\Rule\EqualToValue; },
    'float'                 => function() { return new \Aura\Filter\Rule\Float; },
    'inKeys'                => function() { return new \Aura\Filter\Rule\InKeys; },
    'int'                   => function() { return new \Aura\Filter\Rule\Int; },
    'inValues'              => function() { return new \Aura\Filter\Rule\InValues; },
    'ipv4'                  => function() { return new \Aura\Filter\Rule\Ipv4; },
    'max'                   => function() { return new \Aura\Filter\Rule\Max; },
    'min'                   => function() { return new \Aura\Filter\Rule\Min; },
    'regex'                 => function() { return new \Aura\Filter\Rule\Regex; },
    'strictEqualToField'    => function() { return new \Aura\Filter\Rule\StrictEqualToField; },
    'strictEqualToValue'    => function() { return new \Aura\Filter\Rule\StrictEqualToValue; },
    'string'                => function() { return new \Aura\Filter\Rule\String; },
    'strlenBetween'         => function() { return new \Aura\Filter\Rule\StrlenBetween; },
    'strlenMax'             => function() { return new \Aura\Filter\Rule\StrlenMax; },
    'strlenMin'             => function() { return new \Aura\Filter\Rule\StrlenMin; },
    'strlen'                => function() { return new \Aura\Filter\Rule\Strlen; },
    'trim'                  => function() { return new \Aura\Filter\Rule\Trim; },
    'upload'                => function() { return new \Aura\Filter\Rule\Upload; },
    'url'                   => function() { return new \Aura\Filter\Rule\Url; },
    'word'                  => function() { return new \Aura\Filter\Rule\Word; },
];
