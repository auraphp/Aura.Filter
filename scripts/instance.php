<?php
namespace Aura\Filter;
require_once dirname(__DIR__) . '/src.php';

$ruleRegistry = require __DIR__ . '/registry.php';
$ruleRegistry['any'] = function() use ($ruleRegistry) {
    $rule = new \Aura\Filter\Rule\Any;
    $rule->setRuleLocator(new \Aura\Filter\RuleLocator($ruleRegistry));
    return $rule;
};

return new RuleCollection(
    new RuleLocator($ruleRegistry),
    new Translator(require dirname(__DIR__) . '/intl/en_US.php')
);