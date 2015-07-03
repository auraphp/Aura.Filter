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
 * Validates that the value is in the list of allowed locale codes.
 *
 * @package Aura.Filter
 *
 */
class Locale
{
    /**
     *
     * Valid locale codes; generated via `locale -a` on Mac OS X 10.7.5.
     *
     * @var array
     *
     */
    protected $codes = array(
        'af_ZA', 'am_ET', 'be_BY', 'bg_BG', 'ca_ES', 'cs_CZ', 'da_DK',
        'de_AT', 'de_CH', 'de_DE', 'el_GR', 'en_AU', 'en_CA', 'en_GB',
        'en_IE', 'en_NZ', 'en_US', 'es_ES', 'et_EE', 'eu_ES', 'fi_FI',
        'fr_BE', 'fr_CA', 'fr_CH', 'fr_FR', 'he_IL', 'hi_IN', 'hr_HR',
        'hu_HU', 'hy_AM', 'is_IS', 'it_CH', 'it_IT', 'ja_JP', 'kk_KZ',
        'ko_KR', 'lt_LT', 'nl_BE', 'nl_NL', 'no_NO', 'pl_PL', 'pt_BR',
        'pt_PT', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sr_YU', 'sv_SE',
        'tr_TR', 'uk_UA', 'zh_CN', 'zh_HK', 'zh_TW',
    );

    /**
     *
     * Validates that the value is in the list of allowed locale codes.
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
        return in_array($value, $this->codes);
    }
}
