<?php

/**
 * Abstract for Custom Tables.
 *
 * @since TDB
 *
 * @package \TEC\Common\StellarWP\Shepherd\Abstracts
 */
declare (strict_types=1);
namespace TEC\Common\StellarWP\Shepherd\Abstracts;

use TEC\Common\StellarWP\Schema\Tables\Contracts\Table;
use TEC\Common\StellarWP\DB\DB;
use TEC\Common\StellarWP\Shepherd\Config;
use TEC\Common\StellarWP\Shepherd\Tables\Utility\Safe_Dynamic_Prefix;
use TEC\Common\StellarWP\Shepherd\Traits\Custom_Table_Query_Methods;
use DateTimeInterface;
/**
 * Class Table_Abstract
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd\Abstracts
 */
abstract class Table_Abstract extends Table
{
    use Custom_Table_Query_Methods;
    /**
     * The PHP type for an integer.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const PHP_TYPE_INT = 'int';
    /**
     * The PHP type for a string.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const PHP_TYPE_STRING = 'string';
    /**
     * The PHP type for a float.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const PHP_TYPE_FLOAT = 'float';
    /**
     * The PHP type for a boolean.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const PHP_TYPE_BOOL = 'bool';
    /**
     * The PHP type for a datetime.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const PHP_TYPE_DATETIME = DateTimeInterface::class;
    /**
     * The column type for a bigint.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const COLUMN_TYPE_BIGINT = 'bigint';
    /**
     * The column type for a varchar.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const COLUMN_TYPE_VARCHAR = 'varchar';
    /**
     * The column type for a text.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const COLUMN_TYPE_TEXT = 'text';
    /**
     * The column type for a longtext.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const COLUMN_TYPE_LONGTEXT = 'longtext';
    /**
     * The column type for a timestamp.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const COLUMN_TYPE_TIMESTAMP = 'timestamp';
    public const SQL_RESERVED_DEFAULTS = ['CURRENT_TIMESTAMP', 'CURRENT_DATE', 'CURRENT_TIME'];
    /**
     * The indexes for the table.
     *
     * @since 0.0.1
     *
     * @var array<array<string, string>>
     */
    public const INDEXES = [];
    /**
     * Constructor.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->db = DB::class;
        $this->container = Config::get_container();
    }
    /**
     * Returns the base table name.
     *
     * This method is overridden to use the hook prefix.
     *
     * @since 0.0.1
     *
     * @return string The base table name.
     */
    public static function base_table_name(): string
    {
        $container = Config::get_container();
        return sprintf(static::$base_table_name, $container->get(Safe_Dynamic_Prefix::class)->get());
    }
    /**
     * The schema slug.
     *
     * This method is overridden to use the hook prefix.
     *
     * @since 0.0.1
     *
     * @return string The schema slug.
     */
    public static function get_schema_slug(): string
    {
        return sprintf(static::$schema_slug, Config::get_hook_prefix());
    }
    /**
     * An array of all the columns in the table.
     *
     * @since 0.0.1
     *
     * @return array<string, array<string, string>>
     */
    abstract public static function get_columns(): array;
    /**
     * An array of all the columns that are searchable.
     *
     * @since 0.0.1
     *
     * @return string[]
     */
    public static function get_searchable_columns(): array
    {
        return [];
    }
    /**
     * Helper method to check and add an index to a table.
     *
     * @since 0.0.1
     *
     * @param array  $results    The results array to track changes.
     * @param string $index_name The name of the index.
     * @param string $columns    The columns to index.
     *
     * @return array The updated results array.
     */
    protected function check_and_add_index(array $results, string $index_name, string $columns): array
    {
        $index_name = esc_sql($index_name);
        // Add index only if it does not exist.
        if ($this->has_index($index_name)) {
            return $results;
        }
        $columns = esc_sql($columns);
        DB::query(DB::prepare("ALTER TABLE %i ADD INDEX `{$index_name}` ( {$columns} )", esc_sql(static::table_name(true))));
        return $results;
    }
    /**
     * Returns the table creation SQL in the format supported
     * by the `dbDelta` function.
     *
     * @since 0.0.1
     * @since 0.0.3 Updated to remove an empty line after the columns and before the primary key.
     *
     * @return string The table creation SQL, in the format supported
     *                by the `dbDelta` function.
     */
    public function get_definition()
    {
        global $wpdb;
        $table_name = static::table_name(true);
        $charset_collate = $wpdb->get_charset_collate();
        $uid_column = static::uid_column();
        $columns = static::get_columns();
        $columns_definitions = [];
        foreach ($columns as $column => $definition) {
            $column_sql = "`{$column}` {$definition['type']}";
            if (!empty($definition['length'])) {
                $column_sql .= "({$definition['length']})";
            }
            if (!empty($definition['unsigned'])) {
                $column_sql .= ' UNSIGNED';
            }
            $column_sql .= !empty($definition['nullable']) ? ' NULL' : ' NOT NULL';
            if (!empty($definition['auto_increment'])) {
                $column_sql .= ' AUTO_INCREMENT';
            }
            if (!empty($definition['default'])) {
                $column_sql .= ' DEFAULT ' . (in_array($definition['default'], self::SQL_RESERVED_DEFAULTS, true) || in_array($definition['php_type'], [self::PHP_TYPE_INT, self::PHP_TYPE_BOOL, self::PHP_TYPE_FLOAT], true) ? $definition['default'] : "'{$definition['default']}'");
            }
            $columns_definitions[] = $column_sql;
        }
        $columns_sql = implode(',' . PHP_EOL, $columns_definitions);
        return "\n\t\t\tCREATE TABLE `{$table_name}` (\n\t\t\t\t{$columns_sql},\n\t\t\t\tPRIMARY KEY (`{$uid_column}`)\n\t\t\t) {$charset_collate};\n\t\t";
    }
    /**
     * Add indexes after table creation.
     *
     * @since 0.0.1
     *
     * @param array<string,string> $results A map of results in the format
     *                                      returned by the `dbDelta` function.
     *
     * @return array<string,string> A map of results in the format returned by
     *                              the `dbDelta` function.
     */
    protected function after_update(array $results)
    {
        if (empty(static::INDEXES) || !is_array(static::INDEXES)) {
            return $results;
        }
        foreach (static::INDEXES as $index) {
            $this->check_and_add_index($results, $index['name'], $index['columns']);
        }
        return $results;
    }
    /**
     * Returns the base table name without the dynamic prefix.
     *
     * @since 0.0.1
     *
     * @return string The base table name without the dynamic prefix.
     */
    public static function raw_base_table_name(): string
    {
        return static::$base_table_name;
    }
}