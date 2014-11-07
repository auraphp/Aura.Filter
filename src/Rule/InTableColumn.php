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
use PDO;

/**
 *
 * Rule for an SQL table column value.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class InTableColumn extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_IN_TABLE_COLUMN',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_IN_TABLE_COLUMN',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_IN_TABLE_COLUMN',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_IN_TABLE_COLUMN',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_IN_TABLE_COLUMN',
    ];

    /**
     *
     * A PDO database connection.
     *
     * @var PDO
     *
     */
    protected $pdo;

    /**
     *
     * The prefix to use when quoting identifier names.
     *
     * @var string
     *
     */
    protected $quote_name_prefix = '';

    /**
     *
     * The suffix to use when quoting identifier names.
     *
     * @var string
     *
     */
    protected $quote_name_suffix = '';

    /**
     *
     * Constructor.
     *
     * @param PDO $pdo A PDO database connection.
     *
     */
    public function __construct(
        PDO $pdo,
        $quote_name_prefix = '',
        $quote_name_suffix = ''
    ) {
        $this->pdo = $pdo;
        $this->quote_name_prefix;
        $this->quote_name_suffix;
    }

    /**
     *
     * Validates that the value exists in a table column; the value will be
     * securely bound into a prepared statement.
     *
     * @param string $table The table to select from. This name will not be
     * escaped or otherwise secured. Never pass user input as a name here.
     *
     * @param string $column The column to select for the value. This name will
     * not be escaped or otherwise secured. Never pass user input as a value
     * here.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($table, $column)
    {
        $stm = $this->buildSelect($table, $column);
        $sth = $this->pdo->prepare($stm);
        $sth->bindValue($column, $this->getValue());
        $sth->execute();
        return $sth->fetchColumn() !== false;
    }

    /**
     *
     * Returns the SELECT statement to find the column value.
     *
     * @param string $table The table to select from. This name will not be
     * escaped or otherwise secured. Never pass user input as a value here.
     *
     * @param string $column The column to select for the value. This name will
     * not be escaped or otherwise secured. Never pass user input as a value
     * here.
     *
     * @return string
     *
     */
    protected function buildSelect($table, $column)
    {
        $qt = $this->quoteName($table);
        $qc = $this->quoteName($column);
        return "SELECT {$qc} FROM {$qt} WHERE {$qc} = :{$column}";
    }

    /**
     *
     * Puts quotes around identifier names (table, column, etc.).
     *
     * @param string $name The identifier name to quote. This name will not be
     * escaped or otherwise secured. Never pass user input as a value here.
     *
     * @return string The identifier name with quotes around it.
     *
     */
    protected function quoteName($name)
    {
        return $this->quote_name_prefix . $name . $this->quote_name_suffix;
    }

    /**
     *
     * Cannot sanitize through this rule.
     *
     * @return bool Always false.
     *
     */
    public function sanitize($table, $column)
    {
        return false;
    }
}
