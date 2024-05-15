<?php

use App\Services\Container\App;

$user = App::get('session')->user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if (!empty($title)): ?><?= $title . ' - '; ?><?php endif; ?>Feature Voting by Ismael Murad</title>
    <link href="/public/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <meta name="robots" content="noindex,nofollow,noarchive,nocache">

    <script src="/public/vendor/popperjs/popper.min.js"></script>
    <script src="/public/vendor/bootstrap/js/bootstrap.min.js" crossorigin="anonymous"></script>
</head>
<body class="pb-3">
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" <?php if (null !== $user): ?>href="/dashboard"
           <?php else: ?>href="/vote"<?php endif; ?>>

            <img src="/public/images/feature_voting_logo.png" style="height: 50px" alt="Feature_voting_logo"/>
        </a>
        <?php if (null !== $user): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php if (str_starts_with($_SERVER['REQUEST_URI'], '/dashboard')): ?>active<?php endif; ?>"
                           aria-current="page" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if (str_starts_with($_SERVER['REQUEST_URI'], '/customers')): ?>active<?php endif; ?>"
                           href="/customers">Kunden</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if (str_starts_with($_SERVER['REQUEST_URI'], '/features')): ?>active<?php endif; ?>"
                           href="/features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if (str_starts_with($_SERVER['REQUEST_URI'], '/categories')): ?>active<?php endif; ?>"
                           href="/categories">Kategorien</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if (str_starts_with($_SERVER['REQUEST_URI'], '/suggestions')): ?>active<?php endif; ?>"
                           href="/suggestions">Vorschläge</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">
                            <i class="bi bi-power"></i>
                            <span class="d-inline-block d-md-none">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>
<main class="py-3 py-md-4 py-lg-5">
