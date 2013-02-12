<?php
namespace Aura\Filter;
require_once dirname(__DIR__) . '/src.php';
$rulelocator = require __DIR__ . '/rulelocator.php';
return new RuleCollection(
    $rulelocator,
    new Translator(require dirname(__DIR__) . '/intl/en_US.php')
);
