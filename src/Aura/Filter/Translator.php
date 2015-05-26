<?php
/**
 *
 * This file is part of the Aura Project for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter;

/**
 *
 * Translator to translate the message
 *
 * @package Aura.Filter
 *
 */
class Translator implements TranslatorInterface
{
    /**
     *
     * The array of message keys and translations.
     *
     * @var array
     *
     */
    protected $messages = [];

    /**
     *
     * Constructor.
     *
     * @param array $package The package internationalization values.
     *
     */
    public function __construct(array $package)
    {
        $this->messages = $package['messages'];
    }

    /**
     *
     * Sets a custom message.
     *
     * @param string $key The message key
     *
     * @param string $value The message value.
     *
     * @return void
     *
     */
    public function set($key, $value)
    {
        $this->messages[$key] = $value;
    }

    /**
     *
     * Translate the key with the token values replaced.
     *
     * @param string $key The message key.
     *
     * @param array $tokens_values The message placeholder tokens and their
     * replacement values.
     *
     * @return string The translated string.
     *
     */
    public function translate($key, array $tokens_values = [])
    {
        if (! isset($this->messages[$key])) {
            return $key;
        }

        // retain the message string
        $message = $this->messages[$key];

        // are there token replacement values?
        if (! $tokens_values) {
            // no, return the message string as-is
            return $message;
        }

        // do string replacements
        foreach ($tokens_values as $token => $value) {
            // convert an array to a CSV string
            if (is_array($value)) {
                $value = '"' . implode('", "', $value) . '"';
            }
            $message = str_replace('{' . $token . '}', $value, $message);
        }

        // done!
        return $message;
    }
}
