<?php

namespace App\Services\Http;

class UploadedFile
{
    private string $name;
    private string $tempName;
    private string $path;
    private string $type;
    private int $error;
    private int $size;

    /**
     * @param string $path
     * @param bool $createPath
     * @return bool
     */
    public function moveTo(string $path, bool $createPath = false): bool
    {
        if (true === $createPath) {
            mkdir(dirname($path), 0770, true);
        }

        return move_uploaded_file(
            $this->getTempName(),
            $path
        );
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
     * @return UploadedFile
     */
    public function setName(string $name): UploadedFile
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTempName(): string
    {
        return $this->tempName;
    }

    /**
     * @param string $tempName
     * @return UploadedFile
     */
    public function setTempName(string $tempName): UploadedFile
    {
        $this->tempName = $tempName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return UploadedFile
     */
    public function setPath(string $path): UploadedFile
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return UploadedFile
     */
    public function setType(string $type): UploadedFile
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @param int $error
     * @return UploadedFile
     */
    public function setError(int $error): UploadedFile
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return UploadedFile
     */
    public function setSize(int $size): UploadedFile
    {
        $this->size = $size;
        return $this;
    }
}
