<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

use Aura\Filter\Rule\AbstractStrlen;
/**
 *
 * Strips non-alphabetic characters from the value.
 *
 * @package Aura.Filter
 *
 */
class Alpha extends AbstractStrlen
{
    /**
     *
     * Strips non-alphabetic characters from the value.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool Always true.
     *
     */
    public function __invoke($subject, $field)
    {
        $input_encoding = $this->detectEncoding($subject->$field);
        $subject->$field = $this->convertToUtf8($subject->$field, $input_encoding);
        $subject->$field = preg_replace('/[\P{L}]/u', '', $subject->$field);
        $subject->$field = $this->convertFromUtf8($subject->$field, $input_encoding);
        return true;
    }
}
