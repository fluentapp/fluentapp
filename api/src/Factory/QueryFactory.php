<?php

namespace App\Factory;

use Cake\Database\Connection;
use Cake\Database\Query;
use Cake\Database\Query\SelectQuery;
use Cake\Database\Query\UpdateQuery;
use Cake\Database\Query\InsertQuery;
use RuntimeException;

/**
 * Factory.
 */
final class QueryFactory
{
    private Connection $connection;

    /**
     * The constructor.
     *
     * @param Connection $connection The database connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Create a new 'select' query for the given table.
     *
     * @param string $table The table name
     *
     * @throws RuntimeException
     *
     * @return SelectQuery A new select query
     */
    public function newSelect(string $table): SelectQuery
    {
        return $this->newSelectQuery()->from($table);
    }

    /**
     * Create a new select query
     */
    public function newSelectQuery(): SelectQuery
    {
        return $this->connection->selectQuery();
    }

    /**
     * Create an 'update' statement for the given table.
     *
     * @param string $table The table to update rows from
     * @param array $data The values to be updated
     *
     * @return Query The new update query
     */
    public function newUpdate(string $table, array $data): UpdateQuery
    {
        return $this->newUpdateQuery()->update($table)->set($data);
    }

    /**
     * Create a new update query
     */
    public function newUpdateQuery(): UpdateQuery
    {
        return $this->connection->updateQuery();
    }

    /**
     * Create an 'update' statement for the given table.
     *
     * @param string $table The table to update rows from
     * @param array $data The values to be updated
     *
     * @return Query The new insert query
     */
    public function newInsert(string $table, array $data): InsertQuery
    {
        return $this->newInsertQuery()->insert(array_keys($data))
            ->into($table)
            ->values($data);
    }

    /**
     * Create a new insert query
     */
    public function newInsertQuery(): InsertQuery
    {
        return $this->connection->insertQuery();
    }

    /**
     * Create a 'delete' query for the given table.
     *
     * @param string $table The table to delete from
     *
     * @return Query A new delete query
     */
    public function newDelete(string $table): Query
    {
        return $this->newQuery()->delete($table);
    }
}
