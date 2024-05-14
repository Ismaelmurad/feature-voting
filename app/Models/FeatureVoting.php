<?php

declare(strict_types=1);

namespace App\Models;

class FeatureVoting extends Model
{
    /**
     * @var string The name of the database table
     */
    protected string $table = 'feature_votings';

    public int $feature_id;
    public int $customer_id;
    public int $value;
    public ?string $comment = null;
    public ?string $created_at = null;

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     * @return FeatureVoting
     */
    public function setCustomerId(int $customer_id): FeatureVoting
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getFeatureId(): int
    {
        return $this->feature_id;
    }

    /**
     * @param int $feature_id
     * @return FeatureVoting
     */
    public function setFeatureId(int $feature_id): FeatureVoting
    {
        $this->feature_id = $feature_id;
        return $this;
    }


    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return FeatureVoting
     */
    public function setValue(int $value): FeatureVoting
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @param string|null $created_at
     * @return FeatureVoting
     */
    public function setCreatedAt(?string $created_at): FeatureVoting
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param ?string $comment
     * @return FeatureVoting
     */
    public function setComment(?string $comment): FeatureVoting
    {
        $this->comment = $comment;
        return $this;
    }
}
