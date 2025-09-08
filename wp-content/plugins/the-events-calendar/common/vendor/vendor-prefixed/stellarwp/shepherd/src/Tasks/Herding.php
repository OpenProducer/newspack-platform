<?php

/**
 * Shepherd's herding task.
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd\Tasks;
 */
declare (strict_types=1);
namespace TEC\Common\StellarWP\Shepherd\Tasks;

use TEC\Common\StellarWP\Shepherd\Config;
use TEC\Common\StellarWP\Shepherd\Abstracts\Task_Abstract;
use TEC\Common\StellarWP\Shepherd\Tables\Task_Logs;
use TEC\Common\StellarWP\Shepherd\Contracts\Logger;
use TEC\Common\StellarWP\Shepherd\Loggers\ActionScheduler_DB_Logger;
use TEC\Common\StellarWP\Shepherd\Loggers\DB_Logger;
use TEC\Common\StellarWP\Shepherd\Tables\Tasks;
use TEC\Common\StellarWP\DB\DB;
use Generator;
/**
 * Shepherd's herding task.
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd\Tasks;
 */
class Herding extends Task_Abstract
{
    /**
     * Processes the herding task.
     *
     * @since 0.0.1
     */
    public function process(): void
    {
        DB::beginTransaction();
        $logger = Config::get_container()->get(Logger::class);
        $logs_at_as_table = false;
        $logs_at_own_table = false;
        if ($logger instanceof ActionScheduler_DB_Logger) {
            $logs_at_as_table = true;
        }
        if ($logger instanceof DB_Logger) {
            $logs_at_own_table = true;
        }
        foreach ($this->get_task_ids() as $task_ids) {
            $imploded_task_ids = implode(',', $task_ids);
            if ($logs_at_own_table) {
                DB::query(DB::prepare("DELETE FROM %i WHERE task_id IN ({$imploded_task_ids})", Task_Logs::table_name()));
            }
            if ($logs_at_as_table) {
                foreach ($task_ids as $task_id) {
                    DB::query(DB::prepare('DELETE FROM %i WHERE message LIKE %s', DB::prefix('actionscheduler_logs'), 'shepherd_' . Config::get_hook_prefix() . '||' . $task_id . '||%'));
                }
            }
            DB::query(DB::prepare("DELETE FROM %i WHERE %i IN ({$imploded_task_ids})", Tasks::table_name(), Tasks::uid_column()));
        }
        DB::commit();
        /**
         * Fires when the herding task is processed.
         *
         * @since 0.0.1
         *
         * @param Herding $task The herding task that was processed.
         */
        do_action('shepherd_' . Config::get_hook_prefix() . '_herding_processed', $this);
    }
    /**
     * Gets the herding task's hook prefix.
     *
     * @since 0.0.1
     *
     * @return string The herding task's hook prefix.
     */
    public function get_task_prefix(): string
    {
        return 'shepherd_tidy_';
    }
    /**
     * Gets the task IDs.
     *
     * @since 0.0.1
     *
     * @return Generator<int[]> The task IDs.
     */
    protected function get_task_ids(): Generator
    {
        /**
         * Filters the limit of tasks to herd in a single batch.
         *
         * @since 0.0.1
         *
         * @param int $limit The limit of tasks to herd.
         */
        $batch_size = max(1, (int) apply_filters('shepherd_' . Config::get_hook_prefix() . '_herding_batch_limit', 500));
        $counter = 0;
        while (100 > $counter) {
            $results = array_unique(array_map('intval', (array) DB::get_col(DB::prepare('SELECT DISTINCT(%i) FROM %i WHERE %i NOT IN (SELECT %i FROM %i) LIMIT %d', Tasks::uid_column(), Tasks::table_name(), 'action_id', 'action_id', DB::prefix('actionscheduler_actions'), $batch_size))));
            ++$counter;
            if (empty($results)) {
                break;
            }
            yield $results;
        }
    }
}