<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Strips non-word characters within the value.
 *
 * @package Aura.Filter
 *
 */
class Word
{
    /**
     *
     * Strips non-word characters within the value.
     *
     * Cf. <http://php.net/manual/en/regexp.reference.escape.php>:
     *
     * > A "word" character is any letter or digit or the underscore character,
     * > that is, any character which can be part of a Perl "word". The
     * > definition of letters and digits is controlled by PCRE's character
     * > tables, and may vary if locale-specific matching is taking place. For
     * > example, in the "fr" (French) locale, some character codes greater than
     * > 128 are used for accented letters, and these are matched by \w.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        $subject->$field = preg_replace('/\W/', '', $value);
        return true;
    }
}
