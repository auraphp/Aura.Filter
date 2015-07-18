<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates that the value is composed only of word characters.
 *
 * @package Aura.Filter
 *
 */
class Word
{
    /**
     *
     * Validates that the value is composed only of word characters.
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
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return (bool) preg_match('/^[\p{L}\p{Nd}_]+$/', $value);
    }
}
