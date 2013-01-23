<?php
/**
 * Package prefix for autoloader.
 */
$loader->add('Aura\Filter\\', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');

// RuleCollection
$di->params['Aura\Filter\RuleCollection']['rule_locator'] = $di->lazyNew('Aura\Filter\RuleLocator');
$di->params['Aura\Filter\RuleCollection']['translator'] = $di->lazy(function () use ($di) {
    $translators = $di->get('intl_translator_locator');
    return $translators->get('Aura.Cli');
});

/**
 * Intl
 */
$di->params['Aura\Intl\PackageLocator']['registry']['Aura.Cli'] = [
    'en_US' => function () use ($system) {
        $package = require "$system/package/Aura.Cli/intl/en_US.php";
        return new Aura\Intl\Package(
            $package['formatter'],
            $package['fallback'],
            $package['messages']
        );
    },
];

/**
 * Rules for the locator
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
    'inValues'              => function() { return new \Aura\Filter\Rule\InValues; },
    'int'                   => function() { return new \Aura\Filter\Rule\Int; },
    'ipv4'                  => function() { return new \Aura\Filter\Rule\Ipv4; },
    'max'                   => function() { return new \Aura\Filter\Rule\Max; },
    'min'                   => function() { return new \Aura\Filter\Rule\Min; },
    'regex'                 => function() { return new \Aura\Filter\Rule\Regex; },
    'strictEqualToField'    => function() { return new \Aura\Filter\Rule\StrictEqualToField; },
    'strictEqualToValue'    => function() { return new \Aura\Filter\Rule\StrictEqualToValue; },
    'string'                => function() { return new \Aura\Filter\Rule\String; },
    'strlen'                => function() { return new \Aura\Filter\Rule\Strlen; },
    'strlenBetween'         => function() { return new \Aura\Filter\Rule\StrlenBetween; },
    'strlenMax'             => function() { return new \Aura\Filter\Rule\StrlenMax; },
    'strlenMin'             => function() { return new \Aura\Filter\Rule\StrlenMin; },
    'trim'                  => function() { return new \Aura\Filter\Rule\Trim; },
    'upload'                => function() { return new \Aura\Filter\Rule\Upload; },
    'url'                   => function() { return new \Aura\Filter\Rule\Url; },
    'word'                  => function() { return new \Aura\Filter\Rule\Word; },
];
