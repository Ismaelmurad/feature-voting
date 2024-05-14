<?php

declare(strict_types=1);

namespace App\Services\Database;

use App\Models\Model;
use App\Services\Container\App;
use App\Services\Http\Request;
use App\Services\Session\Session;
use DateTime;
use DateTimeZone;
use Exception;
use PDO;
use PDOStatement;
use stdClass;

class QueryBuilder
{
    /**
     * The used PDO connection
     *
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * @param PDO $pdo The used PDO connection
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Returns all rows found in a Model.
     *
     * @param string $table
     * @param string|null $class
     * @return array
     */
    public function selectAll(string $table, string $class = null): array
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $table);
        $query->execute();

        if (null === $class) {
            return $query->fetchAll(PDO::FETCH_OBJ);
        }

        return $query->fetchAll(PDO::FETCH_CLASS, $class);
    }

    public function getColumn(string $table, string $column): array
    {
        $query = $this->pdo->prepare('SELECT ' . $column . ' FROM ' . $table);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $class
     * @param string $column
     * @param mixed $value
     * @return Model|null
     */

    public function findBy(string $class, string $column, mixed $value): Model|null
    {
        $table = (new $class())->getTable();
        $statement = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE ' . $column . ' = :value');
        $statement->execute([
            'value' => $value,
        ]);

        $result = $statement->fetchObject($class);

        if (false !== $result) {
            return $result;
        }

        return null;
    }

    /** Returns the object of a model, based on ID */
    public function findById(int $id, string $class): Model|null
    {
        $table = (new $class())->getTable();
        $statement = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetchObject($class);

        if (false !== $result) {
            return $result;
        }

        return null;
    }

    /** Returns all objects of a model */
    public function all(string $class): array
    {
        $table = (new $class())->getTable();
        $statement = $this->pdo->prepare('SELECT * FROM ' . $table);
        $statement->execute();

        $results = [];

        while ($row = $statement->fetchObject($class)) {
            $results[] = $row;
        }

        return $results;
    }

    /**
     * @throws Exception
     */
    public function insert(Model $model): Model
    {
        if (
            property_exists($model, 'created_at') &&
            method_exists($model, 'setCreatedAt') &&
            !isset($model->created_at)
        ) {
            $now = new DateTime('now', new DateTimeZone('UTC'));
            $model->setCreatedAt($now->format('Y-m-d H:i:s'));
        }

        $payload = get_object_vars($model);
        $payload = array_filter($payload, function ($value) {
            return $value !== null;
        });

        $keys = array_keys($payload);
        $keyString = join(', ', $keys);
        $placeholders = array_map(
            function ($key) {
                return ':' . $key;
            },
            $keys
        );

        $sql = 'INSERT INTO ' . $model->getTable() . ' (' . $keyString . ') VALUES (' . join(', ', $placeholders) . ')';
        $query = $this->pdo->prepare($sql);

        try {
            $query->execute($payload);
            $model->setId((int)$this->pdo->lastInsertId());
        } catch (Exception $e) {
            dd($e);
        }

        return $model;
    }

    /**
     * @throws Exception
     */
    public function update(Model $model): Model
    {
        if (property_exists($model, 'updated_at') && method_exists($model, 'setUpdatedAt')) {
            $now = new DateTime('now', new DateTimeZone('UTC'));
            $model->setUpdatedAt($now->format('Y-m-d H:i:s'));
        }

        $payload = get_object_vars($model);
        $sql = 'UPDATE ' . $model->getTable() . ' SET ';

        foreach ($payload as $key => $value) {
            $sql .= $key . ' = :' . $key . ',';
        }

        $sql = rtrim($sql, ',');
        $sql .= ' WHERE id = ' . $model->getId();
        $query = $this->pdo->prepare($sql);

        try {
            $query->execute($payload);
        } catch (Exception $e) {
            die('An error occurred whilst updating in the database.');
        }

        return $model;
    }

    public function delete(Model $entity): void
    {
        try {
            $sql = 'DELETE FROM ' . $entity->getTable() . ' WHERE id = ' . $entity->getId();
            $this->pdo->prepare($sql)->execute();
        } catch (Exception $e) {
            die('An error occurred whilst deleting from database.');
        }
    }

    public function countRows($table): ?int
    {
        $sql = 'SELECT COUNT(*) AS row_count FROM ' . $table;

        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $result = $statement->fetchObject();
        return $result->row_count;
    }

    public function sum($table, $column): ?int
    {
        $sql = 'SELECT SUM(' . $column . ') AS column_sum FROM ' . $table;

        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $result = $statement->fetchObject();
        return $result->column_sum;
    }

    public function selectSpecific($table, $offset, $limit): bool|array
    {
        $sql = 'SELECT * FROM ' . $table . ' LIMIT ' . $offset . ',' . $limit;

        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function paginate(string $table): array
    {
        /* @var Session $session Get session instance */
        $session = App::get('session');

        // Define items per page
        $limit = (int)Request::input(
            'limit',
            $session->get('limit', 10)
        );

        // Define current page
        $page = (int)Request::input('page', 1);

        // Get total number of rows and pages
        $totalEntries = $this->countRows($table);
        $totalPages = (int)ceil($totalEntries / $limit);

        if ($page < 1) {
            $page = 1;
        } elseif ($page > $totalPages) {
            $page = $totalPages;
        }

        if (1 === $page || 0 === $page) {
            $offset = 0;
        } else {
            $offset = ($page * $limit) - $limit;
        }

        $entries = $this->selectSpecific($table, $offset, $limit);

        return [
            'totalEntries' => $totalEntries,
            'entries' => $entries,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'page' => $page,
        ];
    }

    /**
     * @param string $class
     * @return Query
     */
    public function createQuery(string $class): Query
    {
        $table = (new $class())->getTable();
        return new Query($this, $table, $class);
    }

    public function prepare(string $statement): bool|PDOStatement
    {
        return $this->pdo->prepare($statement);
    }

    /**
     * @param string $sql
     * @return stdClass[]|false|null
     */
    public function raw(string $sql): bool|array|null
    {
        $statement = $this->pdo->query($sql);
        return $statement->fetchAll();
    }
}
