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

use Aura\Filter\AbstractRule;

/**
 *
 * Validates that a value is a URL.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Url extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_URL',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_URL',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_URL',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_URL',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_URL',
    ];

    /**
     *
     * Validates the value as a URL.
     *
     * The value must match a generic URL format; for example,
     * ``http://example.com``, ``mms://example.org``, and so on.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate()
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }

        // first, make sure there are no invalid chars, list from ext/filter
        $other = "$-_.+"        // safe
               . "!*'(),"       // extra
               . "{}|\\^~[]`"   // national
               . "<>#%\""       // punctuation
               . ";/?:@&=";     // reserved

        $valid = 'a-zA-Z0-9' . preg_quote($other, '/');
        $clean = preg_replace("/[^$valid]/", '', $value);
        if ($value != $clean) {
            return false;
        }

        // now make sure it parses as a URL with scheme and host
        $result = @parse_url($value);
        if (empty($result['scheme']) || trim($result['scheme']) == '' ||
            empty($result['host'])   || trim($result['host']) == '') {
            // need a scheme and host
            return false;
        } else {
            // looks ok
            return true;
        }
    }

    /**
     *
     * Cannot fix URLs
     *
     * @return bool Always false.
     *
     */
    public function sanitize()
    {
        return false;
    }
}
