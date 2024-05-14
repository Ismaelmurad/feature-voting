<?php

declare(strict_types=1);

namespace App\Services\Session;

use App\Models\Customer;
use App\Models\User;

class Session
{
    private ?User $user = null;
    private ?Customer $customer = null;

    public function __construct()
    {
        if (false === session_id()) {
            $this->start();
        }

        if (null !== $this->get('user_id')) {
            $userId = (int)$this->get('user_id');
            $this->user = User::find($userId);
        }

        if (null !== $this->get('customer_id')) {
            $customerId = (int)$this->get('customer_id');
            $this->customer = Customer::find($customerId);
        }
    }

    /** Starts a session */
    public function start(): void
    {
        session_start();
    }

    /** Destroys current session */
    public function destroy(): void
    {
        session_destroy();
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function customer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): Session
    {
        $this->customer = $customer;
        $this->set('customer_id', $customer->getId());

        return $this;
    }
}
