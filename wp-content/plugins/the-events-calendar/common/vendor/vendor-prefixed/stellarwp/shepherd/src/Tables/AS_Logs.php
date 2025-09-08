<?php

/**
 * The Action Scheduler logs table schema.
 *
 * @since 0.0.1
 *
 * @package TEC\Common\StellarWP\Shepherd\Tables;
 */
namespace TEC\Common\StellarWP\Shepherd\Tables;

use TEC\Common\StellarWP\Shepherd\Abstracts\Table_Abstract as Table;
use TEC\Common\StellarWP\Shepherd\Log;
use TEC\Common\StellarWP\Shepherd\Config;
use TEC\Common\StellarWP\DB\DB;
use DateTime;
/**
 * Action Scheduler logs table schema.
 *
 * This is used only as an interface and should not be registered as a table for schema to handle.
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd\Tables;
 */
class AS_Logs extends Table
{
    /**
     * The base table name, without the table prefix.
     *
     * @since 0.0.1
     *
     * @var string
     */
    protected static $base_table_name = 'actionscheduler_logs';
    /**
     * The field that uniquely identifies a row in the table.
     *
     * @since 0.0.1
     *
     * @var string
     */
    protected static $uid_column = 'log_id';
    /**
     * An array of all the columns in the table.
     *
     * @since 0.0.1
     *
     * @return array<string, array<string, bool|int|string>>
     */
    public static function get_columns(): array
    {
        return [static::$uid_column => ['type' => self::COLUMN_TYPE_BIGINT, 'php_type' => self::PHP_TYPE_INT, 'length' => 20, 'unsigned' => true, 'auto_increment' => true, 'nullable' => false], 'action_id' => ['type' => self::COLUMN_TYPE_BIGINT, 'php_type' => self::PHP_TYPE_INT, 'length' => 20, 'unsigned' => true, 'nullable' => false], 'message' => ['type' => self::COLUMN_TYPE_TEXT, 'php_type' => self::PHP_TYPE_STRING, 'nullable' => false], 'log_date_gmt' => ['type' => self::COLUMN_TYPE_TIMESTAMP, 'php_type' => self::PHP_TYPE_DATETIME, 'nullable' => true, 'default' => '0000-00-00 00:00:00'], 'log_date_local' => ['type' => self::COLUMN_TYPE_TIMESTAMP, 'php_type' => self::PHP_TYPE_DATETIME, 'nullable' => true, 'default' => '0000-00-00 00:00:00']];
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
        foreach (self::fetch_all_where(DB::prepare('WHERE message LIKE %s', 'shepherd_' . Config::get_hook_prefix() . '||' . $task_id . '||%'), 50, ARRAY_A, 'log_date_gmt ASC') as $log_array) {
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
        $log->set_id($model_array['log_id']);
        $log->set_action_id($model_array['action_id']);
        $log->set_date(DateTime::createFromFormat('Y-m-d H:i:s', $model_array['log_date_gmt']));
        $message = explode('||', $model_array['message']);
        $log->set_task_id((int) ($message[1] ?? 0));
        $log->set_type($message[2] ?? '');
        $log->set_level($message[3] ?? '');
        $log->set_entry($message[4] ?? '');
        return $log;
    }
}