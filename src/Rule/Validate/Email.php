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
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates that a value is an email address.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Email
{
    /**
     *
     * The email validation regex.
     *
     * @var string
     *
     */
    protected $expr;

    /**
     *
     * Post-construction tasks to complete object construction.
     *
     * @return void
     *
     */
    public function __construct()
    {
        $qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';

        $dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';

        $atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'
              . '\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';

        $quoted_pair = '\\x5c[\\x00-\\x7f]';

        $domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";

        $quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";

        $domain_ref = $atom;

        $sub_domain = "($domain_ref|$domain_literal)";

        $word = "($atom|$quoted_string)";

        $domain = "$sub_domain(\\x2e$sub_domain)+";

        $local_part = "$word(\\x2e$word)*";

        $this->expr = "$local_part\\x40$domain";
    }

    /**
     *
     * Validates that the value is an email address.
     *
     * Taken directly from <http://www.iamcal.com/publish/articles/php/parsing_email/>.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($object, $field)
    {
        return (bool) preg_match("!^{$this->expr}$!D", $object->$field);
    }
}
