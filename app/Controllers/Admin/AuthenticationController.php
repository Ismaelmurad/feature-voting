<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\User;
use App\Services\Container\App;
use App\Services\Http\RedirectResponse;
use App\Services\Http\Request;
use App\Services\Http\Response;
use App\Services\Http\ResponseInterface;

class AuthenticationController extends Controller
{
    private ?User $user = null;

    /**
     * Shows login form.
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->view('login');
    }

    /**
     * Validates the login form and redirects to the admin area on success.
     *
     * @return ResponseInterface
     */
    public function check(): ResponseInterface
    {
        if (null !== Request::input('submit')) {
            $errors = $this->validate();

            if (0 === count($errors)) {
                $session = App::get('session');
                $session->set('user_id', $this->user->getId());

                return $this->redirect('/dashboard');
            }

            return $this->view('login', [
                'errors' => $errors,
                'old' => Request::input(),
            ]);
        }

        return $this->redirectNotFound();
    }

    /**
     * Validates the form data for the login.
     *
     * @return array Validation errors
     */
    private function validate(): array
    {
        $username = Request::input('username');

        if (empty($username)) {
            $isValid = false;
        } else {
            $this->user = User::findBy('username', $username);

            if (null === $this->user) {
                $isValid = false;
            } else {
                $isValid = password_verify(
                    Request::input('password'),
                    $this->user->getHash()
                );
            }
        }

        if (!($this->user instanceof User) || false === $isValid) {
            return [
                'username' => 'Die Nutzername- und Passwortkombination ist inkorrekt',
            ];
        }

        return [];
    }

    /**
     * Destroys the session and redirects the user to the login form.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $session = App::get('session');
        $session->destroy();

        return $this->redirect('/login');
    }

    public function permissionsInvalid(): ResponseInterface
    {
        return $this->view('/permissionsInvalid');
    }

    public function tokenError(): ResponseInterface
    {
        return $this->view('tokenError');
    }
}
