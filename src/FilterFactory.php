<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter;

/**
 *
 * Factory to create Filter objects.
 *
 * @package Aura.Filter
 *
 */
class FilterFactory
{
    /**
     *
     * Returns a new Filter instance.
     *
     * @return RuleCollection
     *
     */
    public function newInstance()
    {
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
            ))
        );
    }
}
