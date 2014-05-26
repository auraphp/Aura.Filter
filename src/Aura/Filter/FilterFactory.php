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
     * @param object $helpers An arbitrary helper manager for the Filter; if not
     * specified, uses the HelperRegistry from this package.
     *
     * @return Filter
     *
     */
    public function newInstance($helpers = null)
    {
        if (! $helpers) {
            $helpers = new HelperRegistry;
        }

        return new Filter(
            new TemplateRegistry,
            new TemplateRegistry,
            $helpers
        );
    }
}