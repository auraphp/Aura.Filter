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

use Aura\Filter\Rule\Any;

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
            new RuleLocator(
                ['any' => function () {
                    $rule = new Any;
                    $rule->setRuleLocator(new RuleLocator(
                        // plug here the stuff @todo
                    ));

                    return $rule;
                }]
            )
        );
    }
}
