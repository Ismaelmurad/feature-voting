<?php

namespace App\Services\Http;

interface ResponseInterface
{
    public function getStatusCode(): int;

    public function getContentType(): string;

    public function getContent(): ?string;

    public function send(): void;
}
