<?php

/**
 * Shepherd's wrapper of Action Scheduler methods.
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd;
 */
declare (strict_types=1);
namespace TEC\Common\StellarWP\Shepherd;

use ActionScheduler;
use ActionScheduler_Action;
use ActionScheduler_FinishedAction;
use RuntimeException;
/**
 * Shepherd's wrapper of Action Scheduler methods.
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd;
 */
class Action_Scheduler_Methods
{
    /**
     * Checks if an action is scheduled.
     *
     * @since 0.0.1
     *
     * @param string $hook The hook of the action.
     * @param array  $args The arguments of the action.
     * @param string $group The group of the action.
     *
     * @return bool Whether the action is scheduled.
     */
    public static function has_scheduled_action(string $hook, array $args = [], string $group = ''): bool
    {
        return as_has_scheduled_action($hook, $args, $group);
    }
    /**
     * Schedules a single action.
     *
     * @since 0.0.1
     *
     * @param int    $timestamp The timestamp of the action.
     * @param string $hook      The hook of the action.
     * @param array  $args      The arguments of the action.
     * @param string $group     The group of the action.
     * @param bool   $unique    Whether the action should be unique.
     * @param int    $priority  The priority of the action.
     *
     * @return int The action ID.
     */
    public static function schedule_single_action(int $timestamp, string $hook, array $args = [], string $group = '', bool $unique = false, int $priority = 10): int
    {
        return as_schedule_single_action($timestamp, $hook, $args, $group, $unique, $priority);
    }
    /**
     * Gets an action by its ID.
     *
     * @since 0.0.1
     *
     * @param int $action_id The action ID.
     *
     * @return ActionScheduler_Action The action.
     *
     * @throws RuntimeException If the action is not found.
     */
    public static function get_action_by_id(int $action_id): ActionScheduler_Action
    {
        $store = ActionScheduler::store();
        $action = $store->fetch_action($action_id);
        if (!$action instanceof ActionScheduler_Action) {
            throw new RuntimeException('Action not found.');
        }
        return $action;
    }
    /**
     * Gets actions by their IDs.
     *
     * @since 0.0.1
     *
     * @param array $action_ids The action IDs.
     *
     * @return ActionScheduler_Action[] The actions.
     *
     * @throws RuntimeException If an action is not found.
     */
    public static function get_actions_by_ids(array $action_ids): array
    {
        $store = ActionScheduler::store();
        $actions = [];
        foreach ($action_ids as $action_id) {
            $action = $store->fetch_action($action_id);
            if (!$action instanceof ActionScheduler_Action) {
                throw new RuntimeException('Action not found.');
            }
            $actions[$action_id] = $action;
        }
        return $actions;
    }
    /**
     * Gets pending actions by their IDs.
     *
     * @since 0.0.1
     *
     * @param array $action_ids The action IDs.
     *
     * @return ActionScheduler_Action[] The pending actions.
     */
    public static function get_pending_actions_by_ids(array $action_ids): array
    {
        $actions = self::get_actions_by_ids($action_ids);
        return array_filter($actions, fn(ActionScheduler_Action $action) => !$action instanceof ActionScheduler_FinishedAction);
    }
}