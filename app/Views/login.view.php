<?php

use App\Controllers\Controller;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Feature Voting by Ismael Murad</title>
    <link href="/public/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <script src="/public/vendor/popperjs/popper.min.js"></script>
    <script src="/public/vendor/bootstrap/js/bootstrap.min.js"
            crossorigin="anonymous"></script>

    <meta name="robots" content="noindex,nofollow,noarchive,nocache">
</head>
<body>
<main>
    <div class="w-100 d-flex justify-content-center align-items-center" style="height: 100vh;">

        <div class="card border-0 p-5">

            <div class="text-center mb-2">

                                <img src="/public/images/feature_voting_logo.png" style="height: 50px" alt="Logo"/>
            </div>

            <div class="mt-3">
                <form method="post" action="/authentication">
                    <input type="hidden" name="submit" value="1">
                    <div class="form-group mb-3">
                        <label for="username">Benutzername</label>
                        <input
                                type="text"
                                name="username"
                                class="form-control <?= isset($errors['username']) ? 'is-invalid' : ''; ?>"
                                id="username"
                                placeholder="Benutzername"
                                value="<?= $old['username'] ?? ''; ?>"
                                required
                        >
                        <?php if (isset($errors['username'])): ?>
                            <div class="invalid-feedback"><?= $errors['username']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="password">Passwort</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               id="password"
                               placeholder="Passwort"
                               required>
                    </div>
                    <button type="submit" class="btn btn-ismael mt-2 d-block w-100 mt-4">Login</button>
                </form>
            </div>
        </div>
    </div>
    <?php Controller::partial('partials/footer'); ?>
</main>


