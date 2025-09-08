<?php

/**
 * Provides query methods common to all custom tables.
 *
 * @since 0.0.1
 *
 * @package TEC\Common\StellarWP\Shepherd\Traits;
 */
namespace TEC\Common\StellarWP\Shepherd\Traits;

use Generator;
use TEC\Common\StellarWP\DB\DB;
use InvalidArgumentException;
use DateTimeInterface;
use TEC\Common\StellarWP\Shepherd\Abstracts\Table_Abstract as Table;
use TEC\Common\StellarWP\Shepherd\Contracts\Model;
/**
 * Trait Custom_Table_Query_Methods.
 *
 * @since 0.0.1
 *
 * @package \TEC\Common\StellarWP\Shepherd\Traits;
 */
trait Custom_Table_Query_Methods
{
    /**
     * Fetches all the rows from the table using a batched query.
     *
     * @since 0.0.1
     *
     * @param int    $batch_size   The number of rows to fetch per batch.
     * @param string $output       The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
     * @param string $where_clause The optional WHERE clause to use.
     * @param string $order_by     The optional ORDER BY clause to use.
     *
     * @return Generator<array<string, mixed>> The rows from the table.
     */
    public static function fetch_all(int $batch_size = 50, string $output = OBJECT, string $where_clause = '', string $order_by = ''): Generator
    {
        $fetched = 0;
        $total = null;
        $offset = 0;
        do {
            // On first iteration, we need to set the SQL_CALC_FOUND_ROWS flag.
            $sql_calc_found_rows = 0 === $fetched ? 'SQL_CALC_FOUND_ROWS' : '';
            $uid_column = static::uid_column();
            $order_by = $order_by ?: $uid_column . ' ASC';
            $batch = DB::get_results(DB::prepare("SELECT {$sql_calc_found_rows} * FROM %i {$where_clause} ORDER BY {$order_by} LIMIT %d, %d", static::table_name(true), $offset, $batch_size), $output);
            // We need to get the total number of rows, only after the first batch.
            $total ??= DB::get_var('SELECT FOUND_ROWS()');
            $fetched += count($batch);
            yield from $batch;
        } while ($fetched < $total);
    }
    /**
     * Inserts a single row into the table.
     *
     * @since 0.0.1
     *
     * @param array<mixed> $entry The entry to insert.
     *
     * @return bool|int The number of rows affected, or `false` on failure.
     */
    public static function insert(array $entry)
    {
        return static::insert_many([$entry]);
    }
    /**
     * Updates a single row in the table.
     *
     * @since 0.0.1
     *
     * @param array<mixed> $entry The entry to update.
     *
     * @return bool Whether the update was successful.
     */
    public static function update_single(array $entry): bool
    {
        return static::update_many([$entry]);
    }
    /**
     * Inserts or updates a single row in the table.
     *
     * @since 0.0.1
     *
     * @param array<mixed> $entry The entry to upsert.
     *
     * @return bool Whether the upsert was successful.
     */
    public static function upsert(array $entry): bool
    {
        $uid_column = static::uid_column();
        $uid = $entry[$uid_column] ?? false;
        return $uid ? static::update_single($entry) : static::insert($entry);
    }
    /**
     * Inserts multiple rows into the table.
     *
     * @since 0.0.1
     *
     * @param array<mixed> $entries The entries to insert.
     *
     * @return bool|int The number of rows affected, or `false` on failure.
     */
    public static function insert_many(array $entries)
    {
        [$prepared_columns, $prepared_values] = static::prepare_statements_values($entries);
        return DB::query(DB::prepare("INSERT INTO %i ({$prepared_columns}) VALUES {$prepared_values}", static::table_name(true)));
    }
    /**
     * Updates multiple rows into the table.
     *
     * @since 0.0.1
     *
     * @param array<mixed> $entries The entries to update.
     *
     * @return bool Whether the update was successful.
     */
    public static function update_many(array $entries): bool
    {
        $uid_column = static::uid_column();
        $queries = [];
        $columns = array_keys(static::get_columns());
        foreach ($entries as $entry) {
            $uid = $entry[$uid_column] ?? '';
            if (!$uid) {
                continue;
            }
            $set_statement = [];
            foreach ($entry as $column => $value) {
                if ($column === $uid_column) {
                    continue;
                }
                if (!in_array($column, $columns, true)) {
                    continue;
                }
                if ($value instanceof DateTimeInterface) {
                    $value = $value->format('Y-m-d H:i:s');
                }
                $set_statement[] = DB::prepare("`{$column}` = %s", $value);
            }
            $set_statement = implode(', ', $set_statement);
            $queries[] = DB::prepare("UPDATE %i SET {$set_statement} WHERE {$uid_column} = %s;", static::table_name(true), $uid);
        }
        return (bool) DB::query(implode('', $queries));
    }
    /**
     * Deletes a single row from the table.
     *
     * @since 0.0.1
     *
     * @param int    $uid    The ID of the row to delete.
     * @param string $column The column to use for the delete query.
     *
     * @return bool Whether the delete was successful.
     */
    public static function delete(int $uid, string $column = ''): bool
    {
        return static::delete_many([$uid], $column);
    }
    /**
     * Deletes multiple rows from the table.
     *
     * @since 0.0.1
     *
     * @param array<int|string> $ids    The IDs of the rows to delete.
     * @param string            $column The column to use for the delete query.
     *
     * @return bool|int The number of rows affected, or `false` on failure.
     */
    public static function delete_many(array $ids, string $column = '')
    {
        $ids = array_filter(array_map(fn($id) => is_numeric($id) ? (int) $id : "'{$id}'", $ids));
        if (empty($ids)) {
            return false;
        }
        $prepared_ids = implode(', ', $ids);
        $column = $column ?: static::uid_column();
        return DB::query(DB::prepare("DELETE FROM %i WHERE {$column} IN ({$prepared_ids})", static::table_name(true)));
    }
    /**
     * Prepares the statements and values for the insert and update queries.
     *
     * @since 0.0.1
     *
     * @param array<mixed> $entries The entries to prepare.
     *
     * @return array<string> The prepared statements and values.
     */
    protected static function prepare_statements_values(array $entries): array
    {
        $columns = array_keys($entries[0]);
        $prepared_columns = implode(', ', array_map(static fn(string $column) => "`{$column}`", $columns));
        $prepared_values = implode(', ', array_map(static fn(array $entry) => '(' . implode(', ', array_map(static fn($e) => DB::prepare('%s', $e instanceof DateTimeInterface ? $e->format('Y-m-d H:i:s') : $e), $entry)) . ')', $entries));
        return [$prepared_columns, $prepared_values];
    }
    /**
     * Fetches all the rows from the table using a batched query and a WHERE clause.
     *
     * @since 0.0.1
     *
     * @param string $where_clause The WHERE clause to use.
     * @param int    $batch_size   The number of rows to fetch per batch.
     * @param string $output       The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
     * @param string $order_by     The optional ORDER BY clause to use.
     *
     * @return Generator<array<string, mixed>> The rows from the table.
     */
    public static function fetch_all_where(string $where_clause, int $batch_size = 50, string $output = OBJECT, string $order_by = ''): Generator
    {
        return static::fetch_all($batch_size, $output, $where_clause, $order_by);
    }
    /**
     * Fetches the first row from the table using a WHERE clause.
     *
     * @since 0.0.1
     *
     * @param string $where_clause The prepared WHERE clause to use.
     * @param string $output       The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
     *
     * @return array|object|null The row from the table, or `null` if no row was found.
     */
    public static function fetch_first_where(string $where_clause, string $output = OBJECT)
    {
        return DB::get_row(DB::prepare("SELECT * FROM %i {$where_clause} LIMIT 1", static::table_name(true)), $output);
    }
    /**
     * Method used to paginate the results of a query.
     *
     * Also supports joining another table.
     *
     * @since 0.0.1
     *
     * @param array  $args                      The query arguments.
     * @param int    $per_page                  The number of items to display per page.
     * @param int    $page                      The current page number.
     * @param string $join_table                The table to join.
     * @param string $join_condition            The condition to join on.
     * @param array  $selectable_joined_columns The columns from the joined table to select.
     * @param string $output                    The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
     *
     * @return array The items.
     * @throws InvalidArgumentException If the table to join is the same as the current table.
     *                                  If the join condition does not contain an equal sign.
     *                                  If the join condition does not contain valid columns.
     */
    public static function paginate(array $args, int $per_page = 20, int $page = 1, string $join_table = '', string $join_condition = '', array $selectable_joined_columns = [], string $output = OBJECT): array
    {
        $is_join = (bool) $join_table;
        if ($is_join && static::table_name(true) === $join_table::table_name(true)) {
            throw new InvalidArgumentException('The table to join must be different from the current table.');
        }
        $per_page = min(max(1, $per_page), 200);
        $page = max(1, $page);
        $offset = ($page - 1) * $per_page;
        $orderby = $args['orderby'] ?? static::uid_column();
        $order = strtoupper($args['order'] ?? 'ASC');
        if (!in_array($orderby, array_keys(static::get_columns()), true)) {
            $orderby = static::uid_column();
        }
        if (!in_array($order, ['ASC', 'DESC'], true)) {
            $order = 'ASC';
        }
        $where = static::build_where_from_args($args);
        [$join, $secondary_columns] = $is_join ? static::get_join_parts($join_table, $join_condition, $selectable_joined_columns) : ['', ''];
        return DB::get_results(DB::prepare("SELECT a.*{$secondary_columns} FROM %i a {$join} {$where} ORDER BY a.{$orderby} {$order} LIMIT %d, %d", static::table_name(true), $offset, $per_page), $output);
    }
    /**
     * Gets the total number of items in the table.
     *
     * @since 0.0.1
     *
     * @param array<string,mixed> $args The query arguments.
     *
     * @return int The total number of items in the table.
     */
    public static function get_total_items(array $args = []): int
    {
        $where = static::build_where_from_args($args);
        return (int) DB::get_var(DB::prepare("SELECT COUNT(*) FROM %i a {$where}", static::table_name(true)));
    }
    /**
     * Builds a WHERE clause from the provided arguments.
     *
     * @since 0.0.1
     *
     * @param array<string,mixed> $args   The query arguments.
     *
     * @return string The WHERE clause.
     */
    protected static function build_where_from_args(array $args = []): string
    {
        $query_operator = strtoupper($args['query_operator'] ?? 'AND');
        if (!in_array($query_operator, ['AND', 'OR'], true)) {
            $query_operator = 'AND';
        }
        unset($args['order'], $args['orderby'], $args['query_operator']);
        if (empty($args)) {
            return '';
        }
        $joined_prefix = 'a.';
        $where = [];
        $search = $args['term'] ?? '';
        if ($search) {
            $searchable_columns = static::get_searchable_columns();
            if (!empty($searchable_columns)) {
                $search_where = [];
                foreach ($searchable_columns as $column) {
                    $search_where[] = DB::prepare("{$joined_prefix}{$column} LIKE %s", '%' . DB::esc_like($search) . '%');
                }
                $where[] = '(' . implode(' OR ', $search_where) . ')';
            }
        }
        $columns = array_keys(static::get_columns());
        foreach ($args as $arg) {
            if (!is_array($arg)) {
                continue;
            }
            if (empty($arg['column'])) {
                continue;
            }
            if (!in_array($arg['column'], $columns, true)) {
                continue;
            }
            if (empty($arg['value'])) {
                // We check that the column has any value then.
                $arg['value'] = '';
                $arg['operator'] = '!=';
            }
            if (empty($arg['operator'])) {
                $arg['operator'] = '=';
            }
            // For anything else, you should build your own query!
            if (!in_array($arg['operator'], ['=', '!=', '>', '<', '>=', '<='], true)) {
                $arg['operator'] = '=';
            }
            $column = $arg['column'];
            $operator = $arg['operator'];
            $value = $arg['value'];
            $placeholder = is_numeric($value) ? '%d' : '%s';
            // Only integers and strings are supported currently.
            $where[] = DB::prepare("{$joined_prefix}{$column} {$operator} {$placeholder}", $value);
        }
        if (empty($where)) {
            return '';
        }
        return 'WHERE ' . implode(" {$query_operator} ", $where);
    }
    /**
     * Gets the JOIN parts of the query.
     *
     * @since 0.0.1
     *
     * @param string $join_table                The table to join.
     * @param string $join_condition            The condition to join on.
     * @param array  $selectable_joined_columns The columns from the joined table to select.
     *
     * @return array<string> The JOIN statement and the secondary columns to select.
     * @throws InvalidArgumentException If the join condition does not contain an equal sign.
     *                                  If the join condition does not contain valid columns.
     */
    protected static function get_join_parts(string $join_table, string $join_condition, array $selectable_joined_columns = []): array
    {
        if (!strstr($join_condition, '=')) {
            throw new InvalidArgumentException('The join condition must contain an equal sign.');
        }
        $join_condition = array_map('trim', explode('=', $join_condition, 2));
        $secondary_table_columns = array_keys($join_table::get_columns());
        $both_table_columns = array_merge(array_keys(static::get_columns()), $secondary_table_columns);
        if (!in_array($join_condition[0], $both_table_columns, true) || !in_array($join_condition[1], $both_table_columns, true)) {
            throw new InvalidArgumentException('The join condition must contain valid columns.');
        }
        $join_condition = 'a.' . str_replace(['a.', 'b.'], '', $join_condition[0]) . ' = b.' . str_replace(['a.', 'b.'], '', $join_condition[1]);
        $clean_secondary_columns = [];
        foreach (array_map('trim', $selectable_joined_columns) as $column) {
            if (!in_array($column, $secondary_table_columns, true)) {
                continue;
            }
            $clean_secondary_columns[] = 'b.' . $column;
        }
        $clean_secondary_columns = $clean_secondary_columns ? ', ' . implode(', ', $clean_secondary_columns) : '';
        return [DB::prepare("JOIN %i b ON {$join_condition}", $join_table::table_name(true)), $clean_secondary_columns];
    }
    /**
     * Gets all models by a column.
     *
     * @since 0.0.1
     *
     * @param string $column The column to get the models by.
     * @param mixed  $value  The value to get the models by.
     * @param int    $limit  The limit of models to return.
     *
     * @return Model[] The models, or an empty array if no models are found.
     *
     * @throws InvalidArgumentException If the column does not exist.
     */
    public static function get_all_by(string $column, $value, int $limit = 50): ?array
    {
        [$value, $placeholder] = self::prepare_value_for_query($column, $value);
        $results = [];
        foreach (static::fetch_all_where(DB::prepare("WHERE {$column} = {$placeholder}", $value), $limit, ARRAY_A) as $task_array) {
            if (empty($task_array[static::uid_column()])) {
                continue;
            }
            $results[] = static::get_model_from_array($task_array);
        }
        return $results;
    }
    /**
     * Gets the first model by a column.
     *
     * @since 0.0.1
     *
     * @param string $column The column to get the model by.
     * @param mixed  $value  The value to get the model by.
     *
     * @return ?Model The model, or `null` if no model is found.
     *
     * @throws InvalidArgumentException If the column does not exist.
     */
    public static function get_first_by(string $column, $value): ?Model
    {
        [$value, $placeholder] = self::prepare_value_for_query($column, $value);
        $task_array = static::fetch_first_where(DB::prepare("WHERE {$column} = {$placeholder}", $value), ARRAY_A);
        if (empty($task_array[static::uid_column()])) {
            return null;
        }
        return static::get_model_from_array($task_array);
    }
    /**
     * Prepares a value for a query.
     *
     * @since 0.0.1
     *
     * @param string $column The column to prepare the value for.
     * @param mixed  $value  The value to prepare.
     *
     * @return array<mixed, string> The prepared value and placeholder.
     *
     * @throws InvalidArgumentException If the column does not exist.
     */
    private static function prepare_value_for_query(string $column, $value): array
    {
        $columns = static::get_columns();
        if (!isset($columns[$column])) {
            throw new InvalidArgumentException("Column {$column} does not exist.");
        }
        $column_type = $columns[$column]['php_type'];
        switch ($column_type) {
            case Table::PHP_TYPE_INT:
            case Table::PHP_TYPE_BOOL:
                $value = (int) $value;
                $placeholder = '%d';
                break;
            case Table::PHP_TYPE_STRING:
            case Table::PHP_TYPE_DATETIME:
                $value = $value instanceof DateTimeInterface ? $value->format('Y-m-d H:i:s') : (string) $value;
                $placeholder = '%s';
                break;
            case Table::PHP_TYPE_FLOAT:
                $value = (float) $value;
                $placeholder = '%f';
                break;
            default:
                throw new InvalidArgumentException("Unsupported column type: {$column_type}.");
        }
        return [$value, $placeholder];
    }
    /**
     * Gets a model by its ID.
     *
     * @since 0.0.1
     *
     * @param int $id The ID.
     *
     * @return ?Model The model, or null if not found.
     *
     * @throws InvalidArgumentException If the model class does not implement the Model interface.
     */
    public static function get_by_id(int $id): ?Model
    {
        return static::get_first_by(static::uid_column(), $id);
    }
    // phpcs:disable Squiz.Commenting.FunctionComment.InvalidNoReturn, Generic.CodeAnalysis.UnusedFunctionParameter.Found
    /**
     * Gets a model from an array.
     *
     * @since 0.0.1
     *
     * @param array<string, mixed> $model_array The model array.
     *
     * @return Model The model.
     */
    abstract protected static function get_model_from_array(array $model_array): Model;
    // phpcs:enable Squiz.Commenting.FunctionComment.InvalidNoReturn, Generic.CodeAnalysis.UnusedFunctionParameter.Found
}