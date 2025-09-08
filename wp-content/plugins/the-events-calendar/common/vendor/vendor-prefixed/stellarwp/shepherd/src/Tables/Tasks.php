<?php

/**
 * The Tasks table schema.
 *
 * @since 0.0.1
 *
 * @package TEC\Common\StellarWP\Shepherd\Tables;
 */
namespace TEC\Common\StellarWP\Shepherd\Tables;

use TEC\Common\StellarWP\Shepherd\Abstracts\Table_Abstract as Table;
use TEC\Common\StellarWP\Shepherd\Contracts\Task;
use InvalidArgumentException;
/**
 * Tasks table schema.
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd\Tables;
 */
class Tasks extends Table
{
    /**
     * The indexes for the table.
     *
     * @since 0.0.1
     *
     * @var array<array<string, string>>
     */
    public const INDEXES = [['name' => 'action_id', 'columns' => 'action_id'], ['name' => 'args_hash', 'columns' => 'args_hash'], ['name' => 'class_hash', 'columns' => 'class_hash']];
    /**
     * The schema version.
     *
     * @since 0.0.1
     * @since 0.0.3 Updated to 0.0.2.
     *
     * @var string
     */
    const SCHEMA_VERSION = '0.0.2s';
    /**
     * The base table name, without the table prefix.
     *
     * @since 0.0.1
     *
     * @var string
     */
    protected static $base_table_name = 'shepherd_%s_tasks';
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
    protected static $schema_slug = 'stellarwp-shepherd-%s-tasks';
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
        return [static::$uid_column => ['type' => self::COLUMN_TYPE_BIGINT, 'php_type' => self::PHP_TYPE_INT, 'length' => 20, 'unsigned' => true, 'auto_increment' => true, 'nullable' => false], 'action_id' => ['type' => self::COLUMN_TYPE_BIGINT, 'php_type' => self::PHP_TYPE_INT, 'length' => 20, 'unsigned' => true, 'nullable' => false], 'class_hash' => ['type' => self::COLUMN_TYPE_VARCHAR, 'php_type' => self::PHP_TYPE_STRING, 'length' => 191, 'nullable' => false], 'args_hash' => ['type' => self::COLUMN_TYPE_VARCHAR, 'php_type' => self::PHP_TYPE_STRING, 'length' => 191, 'nullable' => false], 'data' => ['type' => self::COLUMN_TYPE_LONGTEXT, 'php_type' => self::PHP_TYPE_STRING, 'nullable' => true], 'current_try' => ['type' => self::COLUMN_TYPE_BIGINT, 'php_type' => self::PHP_TYPE_INT, 'length' => 20, 'unsigned' => true, 'nullable' => false, 'default' => 0]];
    }
    /**
     * Gets a task by its action ID.
     *
     * @since 0.0.1
     *
     * @param int $action_id  The action ID.
     *
     * @return ?Task The task, or null if not found.
     *
     * @throws InvalidArgumentException If the task class does not implement the Task interface.
     */
    public static function get_by_action_id(int $action_id): ?Task
    {
        /** @var Task|null */
        return self::get_first_by('action_id', $action_id);
    }
    /**
     * Gets a task by its arguments hash.
     *
     * @since 0.0.1
     *
     * @param string $args_hash The arguments hash.
     *
     * @return Task[] The tasks, or an empty array if no tasks are found.
     */
    public static function get_by_args_hash(string $args_hash): array
    {
        /** @var Task[]|null */
        return self::get_all_by('args_hash', $args_hash);
    }
    /**
     * Gets a task from an array.
     *
     * @since 0.0.1
     *
     * @param array<string, mixed> $task_array The task array.
     *
     * @return Task The task.
     *
     * @throws InvalidArgumentException If the task class does not exist or does not implement the Task interface.
     */
    protected static function get_model_from_array(array $task_array): Task
    {
        $task_data = json_decode($task_array['data'] ?? '[]', true);
        $task_class = $task_data['task_class'] ?? '';
        if (!$task_class || !class_exists($task_class)) {
            throw new InvalidArgumentException('The task class does not exist.');
        }
        $task = new $task_class(...$task_data['args'] ?? []);
        if (!$task instanceof Task) {
            throw new InvalidArgumentException('The task class does not implement the Task interface.');
        }
        $task->set_id($task_array[self::$uid_column]);
        $task->set_action_id($task_array['action_id']);
        $task->set_current_try($task_array['current_try']);
        return $task;
    }
}