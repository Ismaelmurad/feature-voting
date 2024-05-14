<?php

declare(strict_types=1);

namespace App\Services\Http;

class RedirectResponse implements ResponseInterface
{
    protected int $statusCode = 301;
    protected string $contentType = 'text/html';
    protected ?string $content = null;
    protected string $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public function send(): void
    {
        $message = match ($this->getStatusCode()) {
            301 => 'Moved Permanently',
            302 => 'Found (Moved Temporarily)',
            307 => 'Temporary Redirect',
            404 => 'Not Found',
        };

        header('HTTP/1.1 ' . $this->getStatusCode() . ' ' . $message);
        header('Location: ' . $this->getLocation());

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

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}
