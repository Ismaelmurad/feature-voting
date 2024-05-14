<?php

declare(strict_types=1);

namespace App\Models;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Writer\GifWriter;
use Endroid\QrCode\Writer\PdfWriter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\SvgWriter;
use Ramsey\Uuid\Uuid;

class Customer extends Model
{
    /**
     * @var string The name of the database table
     */
    protected string $table = 'customers';

    public string $name;
    public ?string $hash = null;
    public ?int $votes_up = null;
    public ?int $votes_down = null;
    public ?int $votes_total = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $first_visit_at = null;
    public ?string $last_visit_at = null;
    public ?string $contact_person = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?int $amount_staff = null;
    public ?float $monthly_sales = null;
    public ?string $email_sent_at = null;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Customer
     */
    public function setName(string $name): Customer
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param string|null $hash
     * @return Customer
     */
    public function setHash(?string $hash): Customer
    {
        $this->hash = $hash;
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
     * @return Customer
     */
    public function setVotesUp(?int $votes_up): Customer
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
     * @return Customer
     */
    public function setVotesDown(?int $votes_down): Customer
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
     * @return Customer
     */
    public function setVotesTotal(?int $votes_total): Customer
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
     * @return Customer
     */
    public function setCreatedAt(?string $created_at): Customer
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
     * @return Customer
     */
    public function setUpdatedAt(?string $updated_at): Customer
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstVisitAt(): ?string
    {
        return $this->first_visit_at;
    }

    /**
     * @param string|null $first_visit_at
     * @return Customer
     */
    public function setFirstVisitAt(?string $first_visit_at): Customer
    {
        $this->first_visit_at = $first_visit_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastVisitAt(): ?string
    {
        return $this->last_visit_at;
    }

    /**
     * @param string|null $last_visit_at
     * @return Customer
     */
    public function setLastVisitAt(?string $last_visit_at): Customer
    {
        $this->last_visit_at = $last_visit_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactPerson(): ?string
    {
        return $this->contact_person;
    }

    /**
     * @param string|null $contact_person
     * @return Customer
     */
    public function setContactPerson(?string $contact_person): Customer
    {
        $this->contact_person = $contact_person;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return Customer
     */
    public function setPhone(?string $phone): Customer
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Customer
     */
    public function setEmail(?string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmountStaff(): ?int
    {
        return $this->amount_staff;
    }

    /**
     * @param int|null $amount_staff
     * @return Customer
     */
    public function setAmountStaff(?int $amount_staff): Customer
    {
        $this->amount_staff = $amount_staff;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMonthlySales(): ?int
    {
        return $this->monthly_sales;
    }

    /**
     * @param float|null $monthly_sales
     * @return Customer
     */
    public function setMonthlySales(?float $monthly_sales): Customer
    {
        $this->monthly_sales = $monthly_sales;
        return $this;
    }

    /**
     * Returns a unique token for a customer.
     *
     * @return string
     */
    public static function generateHash(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        $url = 'http://';

        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $url = 'https://';
        }

        $url .= $_SERVER['HTTP_HOST'];

        return $url . '/vote?token=' . $this->getHash();
    }

    /**
     * @return string|null
     */
    public function getEmailSentAt(): ?string
    {
        return $this->email_sent_at;
    }

    /**
     * @param string|null $email_sent_at
     * @return Customer
     */
    public function setEmailSentAt(?string $email_sent_at): Customer
    {
        $this->email_sent_at = $email_sent_at;
        return $this;
    }

    /**
     * @param string $format
     * @return ResultInterface
     */
    public function getQrCode(string $format = 'svg'): ResultInterface
    {
        $writer = match ($format) {
            'png' => new PngWriter(),
            'gif' => new GifWriter(),
            'pdf' => new PdfWriter(),
            default => new SvgWriter(),
        };

        return Builder::create()
            ->writer($writer)
            ->data($this->getLink())
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(0)
            ->labelText('Ihr Abstimmungslink')
            ->labelFont(new NotoSans(20))
            ->labelAlignment(new LabelAlignmentCenter())
            ->validateResult(false)
            ->build();
    }
}
