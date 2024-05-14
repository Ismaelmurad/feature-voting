<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Container\App;
use App\Services\Database\Query;
use App\Services\Database\QueryBuilder;
use DateTime;
use DateTimeZone;
use Exception;
use ReflectionProperty;

abstract class Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected string $table;

    /**
     * Default ID column for all models
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * Model constructor
     */
    public function __construct()
    {
    }

    /**
     * @param int $id
     * @return static
     */
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns an entity found by ID or name. Returns null, if the entity is not found.
     *
     * @param int $id
     * @return Model|null
     * @throws Exception
     */
    public static function find(int $id): static|null
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = App::get('database');

        return $queryBuilder->findById($id, static::class);
    }

    /**
     * Returns an entity found by ID or name. Returns null, if the entity is not found.
     *
     * @param string $column
     * @param mixed $value
     * @return Model|null
     */
    public static function findBy(string $column, mixed $value): static|null
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = App::get('database');

        return $queryBuilder->findBy(static::class, $column, $value);
    }

    /**
     * Returns all entities.
     *
     * @return static[]
     */
    public static function all(): array
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = App::get('database');

        return $queryBuilder->all(static::class);
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    /**
     * Inserts a new entity into the database and returns the entity.
     *
     * @return static
     * @throws Exception
     */
    public function save(): static
    {
        if (property_exists($this, 'created_at') && null === $this->created_at) {
            try {
                $now = new DateTime('now', new DateTimeZone('UTC'));
                if (method_exists($this, 'setCreatedAt')) {
                    $this->setCreatedAt($now->format('Y-m-d H:i:s'));
                } else {
                    $this->created_at = $now->format('Y-m-d H:i:s');
                }
            } catch (Exception) {
                // do nothing
            }
        }

        if (property_exists($this, 'updated_at') && null === $this->updated_at) {
            try {
                $now = new DateTime('now', new DateTimeZone('UTC'));

                if (method_exists($this, 'setUpdatedAt')) {
                    $this->setUpdatedAt($now->format('Y-m-d H:i:s'));
                } else {
                    $this->updated_at = $now->format('Y-m-d H:i:s');
                }
            } catch (Exception) {
                // do nothing
            }
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = App::get('database');

        if (null !== $this->getId()) {
            return $queryBuilder->update($this);
        }

        return $queryBuilder->insert($this);
    }

    /**
     * Updates an array of attributes and saves the updated entity.
     *
     * @param array $attributes
     * @return static
     * @throws Exception
     */
    public function update(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $setMethodName = 'set' . $this->toCamelCase($key);

            if (method_exists($this, $setMethodName)) {
                $property = new ReflectionProperty(static::class, $key);
                $propertyType = $property->getType()->getName();

                $value = match ($propertyType) {
                    'int' => (int)$value,
                    'float' => (float)$value,
                    'bool' => (bool)$value,
                    default => $value,
                };

                $this->$setMethodName($value);
            } elseif (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = App::get('database');

        return $queryBuilder->update($this);
    }

    /**
     * Returns the camel case version of an attribute name.
     *
     * @param string $attributeName
     * @return string
     */
    private function toCamelCase(
        string $attributeName
    ): string
    {
        $words = array_map(
            function ($word) {
                return ucfirst($word);
            },
            explode('_', $attributeName)
        );

        return join('', $words);
    }

    /**
     * Deletes the entity..
     *
     * @return void
     * @throws Exception
     */
    public function delete(): void
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = App::get('database');
        $queryBuilder->delete($this);
    }

    public static function query(): Query
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = App::get('database');

        return $queryBuilder->createQuery(static::class);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return (array)$this;
    }

    /** Returns an object based on name */
    public static function findByName(string $value): object|null
    {
        return self::findBy('name', $value);
    }
}
