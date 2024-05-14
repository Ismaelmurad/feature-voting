<?php

declare(strict_types=1);

use App\Controllers\Admin\AuthenticationController;
use App\Controllers\Admin\CustomerController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\FeatureCategoryController;
use App\Controllers\Admin\FeatureController;
use App\Controllers\Admin\SuggestionController as AdminSuggestionController;
use App\Controllers\Customers\FeatureController as CustomerFeatureController;
use App\Controllers\Customers\SuggestionController as CustomerSuggestionController;
use App\Controllers\PagesController;
use App\Services\Routing\Router;

/** @var Router $router */

$router->get('', [PagesController::class, 'home']);
$router->get('404', [PagesController::class, 'notFound']);
$router->get('impressum', [PagesController::class, 'legalNotice']);
$router->get('datenschutz', [PagesController::class, 'dataProtection']);

# Dashboard
$router->get('dashboard', [DashboardController::class, 'index']);

# Suggestions
$router->get('suggestions', [AdminSuggestionController::class, 'index']);
$router->get('suggestions/accept', [AdminSuggestionController::class, 'accept']);
$router->get('suggestions/decline', [AdminSuggestionController::class, 'decline']);
$router->get('suggestions/info', [AdminSuggestionController::class, 'info']);
$router->get('suggestions/pictures', [AdminSuggestionController::class, 'pictures']);

$router->get('vote/suggestion', [CustomerSuggestionController::class, 'index']);
$router->post('vote/suggestion/submit', [CustomerSuggestionController::class, 'submit']);
$router->get('suggestions/picture', [AdminSuggestionController::class, 'picture']);

# Customers
$router->get('customers', [CustomerController::class, 'index']);
$router->get('customers/create', [CustomerController::class, 'create']);
$router->post('customers/store', [CustomerController::class, 'store']);
$router->get('customers/delete', [CustomerController::class, 'delete']);
$router->post('customers/delete', [CustomerController::class, 'delete']);
$router->get('customers/show', [CustomerController::class, 'show']);
$router->post('customers/edit', [CustomerController::class, 'update']);
$router->get('customers/edit', [CustomerController::class, 'edit']);
$router->get('customers/mail', [CustomerController::class, 'mail']);

# Features
$router->get('features', [FeatureController::class, 'index']);
$router->get('features/create', [FeatureController::class, 'create']);
$router->post('features/store', [FeatureController::class, 'store']);
$router->get('features/delete', [FeatureController::class, 'delete']);
$router->get('features/edit', [FeatureController::class, 'edit']);
$router->post('features/edit', [FeatureController::class, 'update']);
$router->get('features/info', [FeatureController::class, 'info']);
$router->get('features/comments', [FeatureController::class, 'comments']);

# FeatureCategory
$router->get('categories', [FeatureCategoryController::class, 'index']);
$router->get('categories/create', [FeatureCategoryController::class, 'create']);
$router->get('categories/edit', [FeatureCategoryController::class, 'edit']);
$router->post('categories/edit', [FeatureCategoryController::class, 'update']);
$router->post('categories/store', [FeatureCategoryController::class, 'store']);
$router->get('categories/delete', [FeatureCategoryController::class, 'delete']); // /categories/delete?id=1
$router->get('categories/', [FeatureCategoryController::class, 'index']);

# Auth
$router->get('login', [AuthenticationController::class, 'index']);
$router->post('authentication', [AuthenticationController::class, 'check']);
$router->get('logout', [AuthenticationController::class, 'logout']);
$router->get('permissionsInvalid', [AuthenticationController::class, 'permissionsInvalid']);
$router->get('tokenError', [AuthenticationController::class, 'tokenError']);

# AuthCustomer
$router->get('vote', [CustomerFeatureController::class, 'latest']);
$router->post('vote', [CustomerFeatureController::class, 'vote']);

# Feature Voting
$router->get('vote/latest', [CustomerFeatureController::class, 'latest']);
$router->get('vote/confirmation', [CustomerFeatureController::class, 'confirmation']);
$router->get('vote/expired', [CustomerFeatureController::class, 'expired']);
$router->post('vote/submit', [CustomerFeatureController::class, 'submit']);
$router->get('vote/history', [CustomerFeatureController::class, 'history']);

