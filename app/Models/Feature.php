<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use DateTimeZone;
use Exception;

class Feature extends Model
{
    public string $name;
    public ?int $feature_category_id = null;
    public ?int $suggestion_id = null;
    public ?string $description = null;
    public ?int $votes_up = null;
    public ?int $votes_down = null;
    public ?int $votes_total = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $voting_ends_at = null;
    public ?string $last_visit_at = null;
    public ?int $score = null;
    public ?int $comments = null;

    /**
     * @var string The name of the database table
     */
    protected string $table = 'features';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Feature
     */
    public function setName(string $name): Feature
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Feature
     */
    public function setDescription(?string $description): Feature
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getVotesUp(): ?int
    {
        return $this->votes_up;
    }

    /**
     * @param int|null $votes_up
     * @return Feature
     */
    public function setVotesUp(?int $votes_up): Feature
    {
        $this->votes_up = $votes_up;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getVotesDown(): ?int
    {
        return $this->votes_down;
    }

    /**
     * @param int|null $votes_down
     * @return Feature
     */
    public function setVotesDown(?int $votes_down): Feature
    {
        $this->votes_down = $votes_down;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getVotesTotal(): ?int
    {
        return $this->votes_total;
    }

    /**
     * @param int|null $votes_total
     * @return Feature
     */
    public function setVotesTotal(?int $votes_total): Feature
    {
        $this->votes_total = $votes_total;
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
     * @return Feature
     */
    public function setCreatedAt(?string $created_at): Feature
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
     * @return Feature
     */
    public function setUpdatedAt(?string $updated_at): Feature
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getVotingEndsAt(): ?DateTime
    {
        $value = $this->voting_ends_at;

        if (null !== $value) {
            try {
                $value = new DateTime($this->voting_ends_at, new DateTimeZone('UTC'));
            } catch (Exception $exception) {
                return null;
            }
        }

        return $value;
    }

    /**
     * @param string|null $voting_ends_at
     * @return Feature
     * @throws Exception
     */
    public function setVotingEndsAt(?string $voting_ends_at): Feature
    {
        if (null !== $voting_ends_at) {
            $this->voting_ends_at = (new DateTime($voting_ends_at))
                ->setTime(23, 59, 59)
                ->format('Y-m-d H:i:s');
        } else {
            $this->voting_ends_at = null;
        }

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLastVisitAt(): ?string
    {
        $value = $this->last_visit_at;

        if (null !== $value) {
            try {
                $value = new DateTime($this->last_visit_at, new DateTimeZone('UTC'));
            } catch (Exception $exception) {
                return null;
            }
        }

        return $value;
    }

    /**
     * @param string|null $last_visit_at
     * @return Feature
     */
    public function setLastVisitAt(?string $last_visit_at): Feature
    {
        $this->last_visit_at = $last_visit_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return int|null
     */
    public function getFeatureCategoryId(): ?int
    {
        return $this->feature_category_id;
    }

    /**
     * @param int|null $feature_category_id
     * @return Feature
     */
    public function setFeatureCategoryId(?int $feature_category_id): Feature
    {
        $this->feature_category_id = $feature_category_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSuggestionId(): ?int
    {
        return $this->suggestion_id;
    }

    /**
     * @param int|null $suggestion_id
     * @return Feature
     */
    public function setSuggestionId(?int $suggestion_id): Feature
    {
        $this->suggestion_id = $suggestion_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @param int|null $score
     * @return Feature
     */
    public function setScore(?int $score): Feature
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getComments(): ?int
    {
        return $this->comments;
    }

    /**
     * @param int|null $comments
     * @return Feature
     */
    public function setComments(?int $comments): Feature
    {
        $this->comments = $comments;
        return $this;
    }
}
