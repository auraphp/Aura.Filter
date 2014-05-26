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
 * Rule for locale (language and country) codes.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Locale extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_LOCALE',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_LOCALE',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_LOCALE',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_LOCALE',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_LOCALE',
    ];

    /**
     * 
     * Valid locale codes; generated via `locale -a` on Mac OS X 10.7.5.
     * 
     * @var array
     * 
     */
    protected $codes = [
        'af_ZA', 'am_ET', 'be_BY', 'bg_BG', 'ca_ES', 'cs_CZ', 'da_DK',
        'de_AT', 'de_CH', 'de_DE', 'el_GR', 'en_AU', 'en_CA', 'en_GB',
        'en_IE', 'en_NZ', 'en_US', 'es_ES', 'et_EE', 'eu_ES', 'fi_FI',
        'fr_BE', 'fr_CA', 'fr_CH', 'fr_FR', 'he_IL', 'hi_IN', 'hr_HR',
        'hu_HU', 'hy_AM', 'is_IS', 'it_CH', 'it_IT', 'ja_JP', 'kk_KZ',
        'ko_KR', 'lt_LT', 'nl_BE', 'nl_NL', 'no_NO', 'pl_PL', 'pt_BR',
        'pt_PT', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sr_YU', 'sv_SE',
        'tr_TR', 'uk_UA', 'zh_CN', 'zh_HK', 'zh_TW',
    ];

    /**
     * 
     * Validates that the value is in the list of allowed locale codes.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate()
    {
        $value = $this->getValue();

        return in_array($value, $this->codes);
    }

    /**
     * 
     * Can't fix locale codes.
     * 
     * @return bool Always false.
     * 
     */
    public function sanitize()
    {
        return false;
    }
}
