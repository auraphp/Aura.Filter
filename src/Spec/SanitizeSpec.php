<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Spec;

/**
 *
 * A "sanitize" rule specification.
 *
 * @package Aura.Filter
 *
 */
class SanitizeSpec extends Spec
{
    /**
     *
     * If the field is blank, use this as the replacement value.
     *
     * @param mixed
     *
     */
    protected $blank_value;
    /**
     * 0 = no rand value
     * 1 = unsafe rand value
     * 2 = safe rand value
     * @var int
     */
    protected $blank_rand_value=0;
    protected $blank_rand_value_len=16;
        /**
     *
     * If the field is null, use this as the replacement value.
     *
     * @param mixed
     *
     */
    protected $null_value;
    /**
     * 0 = no rand value
     * 1 = unsafe rand value
     * 2 = safe rand value
     * @var int
     */
    protected $null_rand_value=0;
    protected $null_rand_value_len=16;

    /**
     *
     * Sanitize the field using this rule (blank not allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function to($rule)
    {
        $this->allow_blank = false;
        $this->allow_null = false;
        return $this->init(func_get_args());
    }

    /**
     *
     * Sanitize the using this rule (blank allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function toBlankOr($rule)
    {
        $this->allow_blank = true;
        $this->allow_null = false;
        return $this->init(func_get_args());
    }
    
        /**
     *
     * Sanitize the using this rule (null allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function toNullOr($rule)
    {
        $this->allow_null = true;
        return $this->init(func_get_args());
    }

    /**
     *
     * Use this value for blank fields.
     *
     * @param mixed $blank_value Replace the blank field with this value.
     *
     * @return self
     *
     */
    public function useBlankValue($blank_value,$blank_rand_value=0,$blank_rand_value_len=16)
    {
        $this->allow_blank = true;
        $this->blank_value = $blank_value;
        $this->blank_rand_value = (int)$blank_rand_value;
        $this->blank_rand_value_len = (int)$blank_rand_value_len;
        return $this;
    }
    
        /**
     *
     * Use this value for null fields.
     *
     * @param mixed $null_value Replace the blank field with this value.
     *
     * @return self
     *
     */
    public function useNullValue($null_value,$null_rand_value=0,$null_rand_value_len=16)
    {
        $this->allow_null = true;
        $this->null_value = $null_value;
        $this->null_rand_value = (int)$null_rand_value;
        $this->null_rand_value_len= (int)$null_rand_value_len;
        return $this;
    }
    
    /**
     * Generates a safe/non-safe random string of max length $len
     * @param bool $safe_rand
     * @param int $len
     * @return string
     */
    protected function generateRandValue($safe_rand=2,$len=16) 
    {
        if($safe_rand === 2 && function_exists('openssl_random_pseudo_bytes')) 
        {
            $res_str = base64_encode(openssl_random_pseudo_bytes($len*16,true)) ;
        }
        else
        {
            $res_str = uniqid("",true);
        }
        $res_Len = strlen($res_str);
        
        if($len > $res_Len) {
            return substr($res_str,0,$len);
        }
        return $res_str;
    }
    
     /**
     *
     * Check if the field is allowed to be, and actually is, null.
     *
     * @param mixed $subject The filter subject.
     *
     * @return bool
     *
     */
    protected function applyNull($subject)
    {
        if (! parent::applyNull($subject)) {
            return false;
        }

        $field = $this->field;
        if($this->null_rand_value === 0)
        {
            $subject->$field = $this->null_value;
        }
        else
        {
             $subject->$field=$this->generateRandValue($this->null_rand_value,$this->null_rand_value_len);
        }
        return true;
    }


    /**
     *
     * Check if the field is allowed to be, and actually is, blank.
     *
     * @param mixed $subject The filter subject.
     *
     * @return bool
     *
     */
    protected function applyBlank($subject)
    {
        if (! parent::applyBlank($subject)) {
            return false;
        }

        $field = $this->field;
        if($this->blank_rand_value === 0)
        {
            $subject->$field = $this->blank_value;
        }
        else
        {
             $subject->$field=$this->generateRandValue($this->blank_rand_value,$this->blank_rand_value_len);
        }
        return true;
    }

    /**
     *
     * Returns the default failure message for this rule specification.
     *
     * @return string
     *
     */
    protected function getDefaultMessage()
    {
        return $this->field . ' should have sanitized to '
             . parent::getDefaultMessage();
    }
}
