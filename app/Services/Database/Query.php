<?php

declare(strict_types=1);

namespace App\Services\Database;

use PDO;
use RuntimeException;

final class Query
{
    protected string $table;
    protected ?string $model = null;
    protected array $fields = ['*'];
    protected array $conditions = [];
    protected array $orderBy = [];
    protected ?int $offset = null;
    protected ?int $limit = null;
    protected array $parameters = [];
    protected array $bindings = [];
    protected ?string $keyBy = null;
    private QueryBuilder $queryBuilder;

    public function __construct(
        QueryBuilder $queryBuilder,
        string       $table,
        string       $model = null
    )
    {
        $this->queryBuilder = $queryBuilder;
        $this->table = $table;
        $this->model = $model;
    }

    public function where(string $field, string $operator, mixed $value): Query
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->conditions[] = [
                    'field' => $field,
                    'operator' => $operator,
                    'value' => $v,
                    'logical_operator' => 'AND',
                ];
            }
        } else {
            $this->conditions[] = [
                'field' => $field,
                'operator' => $operator,
                'value' => $value,
                'logical_operator' => 'AND',
            ];
        }

        return $this;
    }

    public function orWhere(string $field, string $operator, mixed $value): Query
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->conditions[] = [
                    'field' => $field,
                    'operator' => $operator,
                    'value' => $v,
                    'logical_operator' => 'OR',
                ];
            }
        } else {
            $this->conditions[] = [
                'field' => $field,
                'operator' => $operator,
                'value' => $value,
                'logical_operator' => 'OR',
            ];
        }

        return $this;
    }

    public function order(string $field, string $direction): Query
    {
        $this->orderBy[] = [
            'field' => $field,
            'direction' => $direction,
        ];

        return $this;
    }

    public function offset(int $offset): Query
    {
        $this->offset = $offset;

        return $this;
    }

    public function limit(int $limit): Query
    {
        $this->limit = $limit;

        return $this;
    }

    public function paginate(
        int $currentPage,
        int $perPage = 15
    ): array
    {
        // Get the total amount of available results
        $countQuery = clone $this;
        $countQuery->keyBy(null);
        $total = $countQuery->count();
        unset($countQuery);

        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        $this->offset = ($currentPage - 1) * $perPage;
        $this->limit = $perPage;

        return [
            'total' => $total,
            'items' => $this->get(),
            'totalPages' => $totalPages,
            'offset' => $this->offset,
            'limit' => $this->limit,
            'page' => $currentPage,
            'perPage' => $perPage,
        ];
    }

    public function count(): int
    {
        $countQuery = clone $this;
        $countQuery->select([
            'COUNT(*) AS total'
        ]);
        $countQuery->model();
        $countResult = $countQuery->get();

        if (false !== $countResult) {
            return $countResult[0]->total;
        }

        return 0;
    }

    public function select(array $fields = []): Query
    {
        if (count($fields) !== 0) {
            $this->fields = $fields;
        }

        return $this;
    }

    public function whereIn(string $field, array $values): Query
    {
        if (0 !== count($values)) {
            $this->conditions[] = [
                'field' => $field,
                'operator' => 'IN',
                'value' => $values,
                'logical_operator' => 'AND',
            ];
        }

        return $this;
    }

    public function whereNotIn(string $field, array $ids): Query
    {
        $this->conditions[] = [
            'field' => $field,
            'operator' => 'NOT IN',
            'value' => $ids,
            'logical_operator' => 'AND',
        ];

        return $this;
    }

    public function whereNotNull(string $field): Query
    {
        $this->conditions[] = [
            'field' => $field,
            'operator' => 'IS NOT',
            'value' => null,
            'logical_operator' => 'AND',
        ];

        return $this;
    }

    public function whereNull(string $field): Query
    {
        $this->conditions[] = [
            'field' => $field,
            'operator' => 'IS',
            'value' => null,
            'logical_operator' => 'AND',
        ];

        return $this;
    }

    public function model(string $model = null): Query
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set column to be used as array key for results.
     *
     * @param ?string $attribute
     * @return $this
     */
    public function keyBy(?string $attribute): Query
    {
        $this->keyBy = $attribute;

        return $this;
    }

    public function get(): bool|array
    {
        $statement = $this->toSql();
        $query = $this->queryBuilder->prepare($statement);

        $query->execute($this->bindings);

        if (null !== $this->keyBy) {
            $results = [];

            while ($row = $query->fetchObject($this->model)) {
                if (false === property_exists($row, $this->keyBy)) {
                    throw new RuntimeException(
                        'Unknown column "' . $this->keyBy . '" in table "' . $this->table . '" used for keyBy().'
                    );
                }

                $key = $row->{$this->keyBy};
                $results[$key] = $row;
            }

            return $results;
        }

        if (null === $this->model) {
            return $query->fetchAll(PDO::FETCH_OBJ);
        }

        return $query->fetchAll(PDO::FETCH_CLASS, $this->model);
    }

    public function getColumn(): bool|array
    {
        $statement = $this->toSql();
        $query = $this->queryBuilder->prepare($statement);
        $query->execute($this->bindings);

        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    public function toSql(): string
    {
        $statement = 'SELECT ';
        $statement .= join(',', $this->fields) . ' ';
        $statement .= 'FROM ' . $this->table . ' ';

        foreach ($this->conditions as $index => $condition) {
            $field = $this->getParameterName($condition['field']);
            $bindings = [];

            if (true === is_array($condition['value'])) {
                foreach ($condition['value'] as $valueIndex => $value) {
                    $valueKey = $field . '_' . $valueIndex;
                    $bindings[$valueKey] = $value;
                }
            } else {
                $bindings = [
                    $field => $condition['value'],
                ];
            }

            foreach ($bindings as $key => $value) {
                $this->bind($key, $value);
            }

            if ($index === 0) {
                $statement .= 'WHERE ';
            } else {
                $statement .= $condition['logical_operator'] . ' ';
            }

            if (true === is_array($condition['value'])) {
                $placeholder = '(:' . join(', :', array_keys($bindings)) . ')';
                $statement .= $condition['field'] . ' ' . $condition['operator'] . ' ' . $placeholder;
            } else {
                $statement .= $condition['field'] . ' ' . $condition['operator'] . ' :' . $field . ' ';
            }
        }

        if (0 !== count($this->orderBy)) {
            $statement .= 'ORDER BY ';
            $orderByList = [];

            foreach ($this->orderBy as $orderBy) {
                $orderByList[] = $orderBy['field'] . ' ' . $orderBy['direction'];
            }

            $statement .= join(',', $orderByList) . ' ';
        }

        if (null !== $this->offset && null !== $this->limit) {
            $statement .= 'LIMIT ' . $this->offset . ',' . $this->limit;
        }

        return trim($statement);
    }

    public function getParameterName(string $field): array|string
    {
        // Field name is not used yet
        if (!in_array($field, $this->parameters)) {
            $this->parameters[] = $field;
            return $field;
        }

        // Field name is already used
        $index = 1;

        do {
            $parameter = $field . '_' . $index;

            if (!in_array($parameter, $this->parameters)) {
                $final = true;
            } else {
                $final = false;
                $index++;
            }

            if ($index === 20) {
                die('Could not generate a parameter name for "' . $field . '"');
            }
        } while ($final === false && $index < 20);

        $this->parameters[] = $parameter;

        return $parameter;
    }

    public function bind(string $parameter, mixed $value): void
    {
        $this->bindings[$parameter] = $value;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }
}
