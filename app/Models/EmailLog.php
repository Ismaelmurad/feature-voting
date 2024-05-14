<?php

declare(strict_types=1);

namespace App\Models;

class EmailLog extends Model
{
    /**
     * @var string The name of the database table
     */
    protected string $table = 'email_log';
    public int $customer_id;
    public string $recipient;
    public string $subject;
    public ?string $text = null;
    public ?string $html = null;
    public string $sent_at;
    public ?string $error = null;

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     * @return EmailLog
     */
    public function setCustomerId(int $customer_id): EmailLog
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     * @return EmailLog
     */
    public function setRecipient(string $recipient): EmailLog
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return EmailLog
     */
    public function setSubject(string $subject): EmailLog
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     * @return EmailLog
     */
    public function setText(?string $text): EmailLog
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHtml(): ?string
    {
        return $this->html;
    }

    /**
     * @param string|null $html
     * @return EmailLog
     */
    public function setHtml(?string $html): EmailLog
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return string
     */
    public function getSentAt(): string
    {
        return $this->sent_at;
    }

    /**
     * @param string $sent_at
     * @return EmailLog
     */
    public function setSentAt(string $sent_at): EmailLog
    {
        $this->sent_at = $sent_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     * @return EmailLog
     */
    public function setError(?string $error): EmailLog
    {
        $this->error = $error;
        return $this;
    }
}

