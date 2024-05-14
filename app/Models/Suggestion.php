<?php

declare(strict_types=1);

namespace App\Models;

class Suggestion extends Model
{
    public int $customer_id;
    public ?int $feature_id;
    public string $name;
    public string $text;
    public ?string $created_at = null;
    public ?string $accepted_at = null;
    public ?string $declined_at = null;

    /**
     * @var string The name of the database table
     */
    protected string $table = 'feature_suggestions';

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     * @return Suggestion
     */
    public function setCustomerId(int $customer_id): Suggestion
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFeatureId(): ?int
    {
        return $this->feature_id;
    }

    /**
     * @param int|null $feature_id
     * @return Suggestion
     */
    public function setFeatureId(?int $feature_id): Suggestion
    {
        $this->feature_id = $feature_id;
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
     * @return Suggestion
     */
    public function setName(string $name): Suggestion
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Suggestion
     */
    public function setText(string $text): Suggestion
    {
        $this->text = $text;
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
     * @return Suggestion
     */
    public function setCreatedAt(?string $created_at): Suggestion
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAcceptedAt(): ?string
    {
        return $this->accepted_at;
    }

    /**
     * @param string|null $accepted_at
     * @return Suggestion
     */
    public function setAcceptedAt(?string $accepted_at): Suggestion
    {
        $this->accepted_at = $accepted_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeclinedAt(): ?string
    {
        return $this->declined_at;
    }

    /**
     * @param string|null $declined_at
     * @return Suggestion
     */
    public function setDeclinedAt(?string $declined_at): Suggestion
    {
        $this->declined_at = $declined_at;
        return $this;
    }
}



