<?php

/**
 * The Task logs table schema.
 *
 * @since 0.0.1
 *
 * @package TEC\Common\StellarWP\Shepherd\Tables;
 */
namespace TEC\Common\StellarWP\Shepherd\Tables;

use TEC\Common\StellarWP\Shepherd\Abstracts\Table_Abstract as Table;
use TEC\Common\StellarWP\Shepherd\Log;
use TEC\Common\StellarWP\DB\DB;
use DateTime;
/**
 * Task logs table schema.
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd\Tables;
 */
class Task_Logs extends Table
{
    /**
     * The indexes for the table.
     *
     * @since 0.0.1
     *
     * @var array<array<string, string>>
     */
    public const INDEXES = [['name' => 'task_id', 'columns' => 'task_id'], ['name' => 'action_id', 'columns' => 'action_id'], ['name' => 'type', 'columns' => 'type'], ['name' => 'level', 'columns' => 'level']];
    /**
     * The schema version.
     *
     * @since 0.0.1
     * @since 0.0.3 Updated to 0.0.3.
     *
     * @var string
     */
    const SCHEMA_VERSION = '0.0.3';
    /**
     * The base table name, without the table prefix.
     *
     * @since 0.0.1
     *
     * @var string
     */
    protected static $base_table_name = 'shepherd_%s_task_logs';
    /**
     * The table group.
     *
     * @since 0.0.1
     *
     * @var string
     */
    protected static $group = 'stellarwp_shepherd';
    /**
     * The slug used to identify the custom table.
     *
     * @since 0.0.1
     *
     * @var string
     */
    protected static $schema_slug = 'stellarwp-shepherd-%s-task-logs';
    /**
     * The field that uniquely identifies a row in the table.
     *
     * @since 0.0.1
     *
     * @var string
     */
    protected static $uid_column = 'id';
    /**
     * An array of all the columns in the table.
     *
     * @since 0.0.1
     *
     * @return array<string, array<string, bool|int|string>>
     */
    public static function get_columns(): array
    {
        return [static::$uid_column => ['type' => self::COLUMN_TYPE_BIGINT, 'php_type' => self::PHP_TYPE_INT, 'length' => 20, 'unsigned' => true, 'auto_increment' => true, 'nullable' => false], 'task_id' => ['type' => self::COLUMN_TYPE_BIGINT, 'php_type' => self::PHP_TYPE_INT, 'length' => 20, 'unsigned' => true, 'nullable' => false], 'action_id' => ['type' => self::COLUMN_TYPE_BIGINT, 'php_type' => self::PHP_TYPE_INT, 'length' => 20, 'unsigned' => true, 'nullable' => false], 'date' => ['type' => self::COLUMN_TYPE_TIMESTAMP, 'php_type' => self::PHP_TYPE_DATETIME, 'nullable' => false, 'default' => 'CURRENT_TIMESTAMP'], 'level' => ['type' => self::COLUMN_TYPE_VARCHAR, 'php_type' => self::PHP_TYPE_STRING, 'length' => 191, 'nullable' => false], 'type' => ['type' => self::COLUMN_TYPE_VARCHAR, 'php_type' => self::PHP_TYPE_STRING, 'length' => 191, 'nullable' => false], 'entry' => ['type' => self::COLUMN_TYPE_LONGTEXT, 'php_type' => self::PHP_TYPE_STRING, 'nullable' => false]];
    }
    /**
     * Gets the logs by task ID.
     *
     * @since 0.0.1
     *
     * @param int $task_id The task ID.
     * @return Log[] The logs for the task.
     */
    public static function get_by_task_id(int $task_id): array
    {
        $results = [];
        foreach (self::fetch_all_where(DB::prepare('WHERE task_id = %d', $task_id), 50, ARRAY_A, 'date ASC') as $log_array) {
            $results[] = self::get_model_from_array($log_array);
        }
        return $results;
    }
    /**
     * Gets a log from an array.
     *
     * @since 0.0.1
     *
     * @param array<string, mixed> $model_array The model array.
     *
     * @return Log The log.
     */
    protected static function get_model_from_array(array $model_array): Log
    {
        $log = new Log();
        $log->set_id($model_array['id']);
        $log->set_task_id($model_array['task_id']);
        $log->set_action_id($model_array['action_id']);
        $log->set_date(DateTime::createFromFormat('Y-m-d H:i:s', $model_array['date']));
        $log->set_level($model_array['level']);
        $log->set_type($model_array['type']);
        $log->set_entry($model_array['entry']);
        return $log;
    }
}