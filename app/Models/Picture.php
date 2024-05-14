<?php

declare(strict_types=1);

namespace App\Models;

class Picture extends Model
{
    /**
     * @var string The name of the database table
     */
    protected string $table = 'suggestion_pictures';

    public string $uuid;
    public int $suggestion_id;
    public string $name;
    public string $name_original;
    public string $mime_type;
    public int $filesize;
    public int $width;
    public int $height;
    public ?int $position = null;
    public ?string $created_at = null;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return Picture
     */
    public function setUuid(string $uuid): Picture
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return int
     */
    public function getSuggestionId(): int
    {
        return $this->suggestion_id;
    }

    /**
     * @param int $suggestion_id
     * @return Picture
     */
    public function setSuggestionId(int $suggestion_id): Picture
    {
        $this->suggestion_id = $suggestion_id;
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
     * @return Picture
     */
    public function setName(string $name): Picture
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameOriginal(): string
    {
        return $this->name_original;
    }

    /**
     * @param string $name_original
     * @return Picture
     */
    public function setNameOriginal(string $name_original): Picture
    {
        $this->name_original = $name_original;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mime_type;
    }

    /**
     * @param string $mime_type
     * @return Picture
     */
    public function setMimeType(string $mime_type): Picture
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    /**
     * @return int
     */
    public function getFilesize(): int
    {
        return $this->filesize;
    }

    /**
     * @param int $filesize
     * @return Picture
     */
    public function setFilesize(int $filesize): Picture
    {
        $this->filesize = $filesize;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return Picture
     */
    public function setWidth(int $width): Picture
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return Picture
     */
    public function setHeight(int $height): Picture
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Picture
     */
    public function setPosition(int $position): Picture
    {
        $this->position = $position;
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
     * @return Picture
     */
    public function setCreatedAt(string $created_at): Picture
    {
        $this->created_at = $created_at;
        return $this;
    }


}
