<?php

declare(strict_types=1);

namespace App\Models;

class FeatureCategory extends Model
{
    /**
     * @var string The name of the categories
     */
    public string $name;

    /**
     * @var string|null The date when the category was created
     */
    public ?string $created_at = null;

    /**
     * @var string|null The date when the category was last updated
     */
    public ?string $updated_at = null;

    /**
     * @var string The name of the database table
     */
    protected string $table = 'feature_categories';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FeatureCategory
     */
    public function setName(string $name): FeatureCategory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     * @return FeatureCategory
     */
    public function setCreatedAt(string $created_at): FeatureCategory
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @param string|null $updated_at
     * @return FeatureCategory
     */
    public function setUpdatedAt(?string $updated_at): FeatureCategory
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}
