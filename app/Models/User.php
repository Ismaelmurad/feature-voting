<?php

declare(strict_types=1);

namespace App\Models;

class User extends Model
{
    public string $name;
    public string $hash;
    public ?bool $readonly;
    public string $uuid;
    /**
     * @var string The name of the database table
     */
    protected string $table = 'users';

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return User
     */
    public function setUuid(string $uuid): User
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return bool
     */
    public function isReadonly(): bool
    {

        return $this->readonly ?? false;
    }

    /**
     * @param bool $readonly
     * @return User
     */
    public function setReadonly(bool $readonly): User
    {
        $this->readonly = $readonly;
        return $this;
    }
}
