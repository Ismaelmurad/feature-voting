<?php

namespace App\Controllers;

use App\Models\Customer;
use App\Models\Picture;
use App\Services\Container\App;
use App\Services\Http\RedirectResponse;
use App\Services\Http\Request;
use App\Services\Http\Response;
use App\Services\Session\Session;
use DateTime;
use DateTimeZone;
use Exception;

class Controller
{
    /**
     * Loads a view file and returns the rendered string.
     *
     * @param string $name
     * @param array $data
     * @return Response
     */
    protected function view(string $name, array $data = []): Response
    {
        ob_start();
        extract($data);
        require VIEW_DIR . "/{$name}.view.php";
        $content = ob_get_clean();

        return (new Response())
            ->setContentType('text/html')
            ->setContent($content);
    }

    /** Verifies whether the user trying to access is logged in as admin */
    protected function guard($preventReadOnlyUser = false): void
    {
        /** @var Session $session */
        $session = App::get('session');

        if (null === $session->user()) {
            $session->destroy();

            $this->redirect('/login')
                ->send();
        }

        if (true === $preventReadOnlyUser && true === $session->user()->isReadonly()) {
            $this->redirect('/permissionsInvalid')
                ->send();
        }
    }

    /**
     * Redirects the browser to a different location.
     *
     * @param string $path
     * @return RedirectResponse
     */
    protected function redirect(string $path): RedirectResponse
    {
        return new RedirectResponse($path);
    }

    /**
     * Redirects to log in, if no valid customer token is passed.
     *
     * @return void
     */
    protected function guardCustomer(): void
    {
        /** @var Session $session */
        $session = App::get('session');
        $currentCustomer = $session->customer();
        $customer = null;
        $token = Request::input('token');

        if (null === $currentCustomer || ($token !== null && $token !== $currentCustomer->getHash())) {
            if (null !== $token) {
                /** @var Customer $customer */
                $customer = Customer::findBy('hash', $token);

                if (null !== $customer) {
                    $session->setCustomer($customer);
                } else {
                    $session->destroy();

                    $this->redirect('/tokenError')
                        ->send();
                }
            }
        } else {
            $customer = $currentCustomer;
            $now = (new DateTime('now'))
                ->setTimezone(new DateTimeZone('UTC'));

            /** Save first visit date */
            if (null === $customer->getFirstVisitAt()) {
                try {
                    $customer
                        ->setFirstVisitAt($now->format('Y-m-d H:i:s'))
                        ->save();
                } catch (Exception) {
                    // ignore
                }
            }

            /** Update last visit date */
            try {
                $customer->setLastVisitAt($now->format('Y-m-d H:i:s'))
                    ->save();
            } catch (Exception) {
                // ignore
            }
        }

        if (null === $customer) {
            $session->destroy();

            $this->redirect('/logout')
                ->send();
        }
    }

    /**
     * Returns a RedirectResponse leading to the default 404 page.
     *
     * @return RedirectResponse
     */
    public function redirectNotFound(): RedirectResponse
    {
        return (new RedirectResponse('/404'))
            ->setStatusCode(404);
    }

    /**
     * Loads a partial and passes optional payload.
     *
     * @param string $path
     * @param array $data
     * @return void
     */
    public static function partial(string $path, array $data = []): void
    {
        ob_start();
        extract($data);
        require VIEW_DIR . "/{$path}.view.php";
        echo ob_get_clean();
    }

    public static function getBase64(string $folder, Picture $picture): string
    {
        $extension = pathinfo($picture->getNameOriginal())['extension'];
        $content = file_get_contents(
            'uploads/'
            . $folder
            . '/'
            . $picture->getName()
            . '/'
            . $picture->getName()
            . '.'
            . $extension
        );
        return base64_encode($content);
    }
}
