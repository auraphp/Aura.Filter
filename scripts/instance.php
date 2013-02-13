<?php
namespace Aura\Filter;
require_once dirname(__DIR__) . '/src.php';
return new RuleCollection(
    new RuleLocator(array_merge(
        require __DIR__ . '/registry.php',
        ['any' => function () {
            $rule = new \Aura\Filter\Rule\Any;
            $rule->setRuleLocator(new \Aura\Filter\RuleLocator(
                require __DIR__ . '/registry.php'
            ));
            return $rule;
        }]
    )),
    new Translator(require dirname(__DIR__) . '/intl/en_US.php')
);
