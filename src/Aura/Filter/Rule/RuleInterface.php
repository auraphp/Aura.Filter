<?php
/**
 * 
 * This file is part of the Aura project for PHP.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Filter\Rule;

use StdClass;

/**
 * 
 * Abstract Rule
 * 
 * @package Aura.Filter
 * 
 */
interface RuleInterface
{
    public function prep(StdClass $data, $field);

    public function getMessage();

    public function getValue();

    public function setValue($value);

    public function is();

    public function isNot();

    public function isBlankOr();

    public function fix();

    public function fixBlankOr();
}

