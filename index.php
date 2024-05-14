<?php
const ROOT_DIR = __DIR__;
const CONFIG_DIR = ROOT_DIR . '/config';
const APP_DIR = ROOT_DIR . '/app';
const VIEW_DIR = APP_DIR . '/Views';

require(ROOT_DIR . '/vendor/autoload.php');
require(APP_DIR . '/bootstrap.php');

use App\Services\Http\Request;
use App\Services\Routing\Router;

Router::load('app/routes.php')
    ->direct(Request::uri(), Request::method());
