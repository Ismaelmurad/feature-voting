<?php

namespace App\Controllers;

use App\Services\Http\Response;
use App\Services\Http\ResponseInterface;

class PagesController extends Controller
{
    /** Returns to the login page */
    public function home(): Response
    {
        return $this->view('login');
    }

    /** Returns the 404 page */
    public function notFound(): Response
    {
        return $this->view('404');
    }

    public function legalNotice(): ResponseInterface
    {
        return $this->view('legalnotice');
    }

    public function dataProtection(): ResponseInterface
    {
        return $this->view('dataprotection');
    }
}
