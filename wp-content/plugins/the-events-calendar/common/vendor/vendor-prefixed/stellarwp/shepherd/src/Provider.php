<?php

/**
 * Shepherd's main service provider.
 *
 * @since 0.0.1
 *
 * @package \StellarWP\Shepherd
 */
declare (strict_types=1);
namespace TEC\Common\StellarWP\Shepherd;

use TEC\Common\StellarWP\Shepherd\Abstracts\Provider_Abstract;
use TEC\Common\StellarWP\Shepherd\Tables\Provider as Tables_Provider;
use TEC\Common\StellarWP\Schema\Config as Schema_Config;
use TEC\Common\StellarWP\DB\DB;
use TEC\Common\StellarWP\Shepherd\Contracts\Logger;
use TEC\Common\StellarWP\Shepherd\Tables\Task_Logs;
use TEC\Common\StellarWP\Shepherd\Tables\Tasks;
use RuntimeException;
/**
 * Main Service Provider
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd;
 */
class Provider extends Provider_Abstract
{
    /**
     * The version of the plugin.
     *
     * @since 0.0.1
     *
     * @var string
     */
    public const VERSION = '0.0.1';
    /**
     * The hook prefix.
     *
     * @since 0.0.1
     *
     * @var string
     */
    protected static string $hook_prefix;
    /**
     * Whether the provider has been registered.
     *
     * @since 0.0.1
     *
     * @var bool
     */
    private static bool $has_registered = false;
    /**
     * Registers Shepherd's specific providers and starts core functionality
     *
     * @since 0.0.1
     *
     * @return void The method does not return any value.
     */
    public function register(): void
    {
        if (self::is_registered()) {
            return;
        }
        $this->require_action_scheduler();
        Schema_Config::set_container(Config::get_container());
        Schema_Config::set_db(DB::class);
        // Manually require functions.php since it's not autoloaded for Strauss compatibility.
        require_once __DIR__ . '/functions.php';
        $this->container->singleton(Logger::class, Config::get_logger());
        $this->container->singleton(Tables_Provider::class);
        $this->container->singleton(Regulator::class);
        $this->container->get(Tables_Provider::class)->register();
        $this->container->get(Regulator::class)->register();
        add_action('action_scheduler_deleted_action', [$this, 'delete_tasks_on_action_deletion']);
        self::$has_registered = true;
    }
    /**
     * Requires Action Scheduler.
     *
     * @since 0.0.1
     * @since 0.0.2
     *
     * @return void
     *
     * @throws RuntimeException If Action Scheduler is not found.
     */
    private function require_action_scheduler(): void
    {
        // This is true when we are not running as a Composer package.
        if (file_exists(__DIR__ . '/../vendor/woocommerce/action-scheduler/action-scheduler.php')) {
            require_once __DIR__ . '/../vendor/woocommerce/action-scheduler/action-scheduler.php';
            return;
        }
        // This is true when we are running as a Composer package.
        if (file_exists(__DIR__ . '/../../../woocommerce/action-scheduler/action-scheduler.php')) {
            require_once __DIR__ . '/../../../woocommerce/action-scheduler/action-scheduler.php';
            return;
        }
        // This is true when we are running as a Composer package but prefixed by Strauss or Mozart or similar.
        if (file_exists(__DIR__ . '/../../../../woocommerce/action-scheduler/action-scheduler.php')) {
            require_once __DIR__ . '/../../../../woocommerce/action-scheduler/action-scheduler.php';
            return;
        }
        throw new RuntimeException('Action Scheduler not found');
    }
    /**
     * Resets the registered state.
     *
     * @since 0.0.1
     *
     * @return void
     */
    public static function reset(): void
    {
        self::$has_registered = false;
    }
    /**
     * Checks if Shepherd is registered.
     *
     * @since 0.0.1
     *
     * @return bool
     */
    public static function is_registered(): bool
    {
        return self::$has_registered;
    }
    /**
     * Deletes tasks on action deletion.
     *
     * @since 0.0.1
     *
     * @param int $action_id The action ID.
     */
    public function delete_tasks_on_action_deletion(int $action_id): void
    {
        $task_ids = DB::get_col(DB::prepare('SELECT DISTINCT(%i) FROM %i WHERE %i = %d', Tasks::uid_column(), Tasks::table_name(), 'action_id', $action_id));
        if (empty($task_ids)) {
            return;
        }
        $task_ids = implode(',', array_unique(array_map('intval', $task_ids)));
        DB::query(DB::prepare("DELETE FROM %i WHERE %i IN ({$task_ids})", Task_Logs::table_name(), 'task_id'));
        DB::query(DB::prepare("DELETE FROM %i WHERE %i IN ({$task_ids})", Tasks::table_name(), Tasks::uid_column()));
    }
}