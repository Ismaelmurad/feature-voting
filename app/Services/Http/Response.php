<?php

declare(strict_types=1);

namespace App\Services\Http;

class Response implements ResponseInterface
{
    protected int $statusCode = 200;
    protected string $contentType = 'text/html';
    protected ?string $content = null;

    public function send(): void
    {
        header('Content-Type: ' . $this->getContentType());

        if (null !== $this->content) {
            echo $this->content;
        }

        exit();
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContent(?string $content = null): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
