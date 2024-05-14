<?php
/** @var Suggestion $suggestion */
/** @var Picture[] $pictures */
/** @var Customer $customer */

use App\Controllers\Controller;
use App\Models\Customer;
use App\Models\Picture;
use App\Models\Suggestion;

Controller::partial(
        'partials/head',
        [
                'title' => $suggestion->name . '- Vorschläge',
        ]
); ?>
<link rel="stylesheet" href="/public/vendor/lightbox-2/css/lightbox.min.css"/>

<div class="container">
    <h1 class="page-title pb-5">Vorschlag: <?= htmlentities($suggestion->name); ?></h1>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6 offset-md-1 offset-lg-2 offset-xl-3 bg-white p-3">
            <?php if (0 !== count($pictures)) : ?>
                <div class="mb-4 row">
                    <?php foreach ($pictures as $index => $picture): ?>
                        <div class="col-3">
                            <a href="/suggestions/picture?id=<?= $picture->getUuid(); ?>"
                               data-lightbox="pictures"
                            >
                                <img src="/suggestions/picture?id=<?= $picture->getUuid(); ?>"
                                     alt="Bild <?= $index + 1; ?>"
                                     class="img-fluid">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered w-100">
                <thead>
                <tbody>
                <tr>
                    <th>ID:</th>
                    <td> <?= $suggestion->id; ?> </td>
                </tr>

                <tr>
                    <th>Name:</th>
                    <td><?= htmlentities($suggestion->name); ?> </td>
                </tr>

                <tr>
                    <th>Text:</th>
                    <td><?= htmlentities($suggestion->text); ?> </td>
                </tr>

                <tr>
                    <th>Kunde:</th>
                    <td><?= htmlentities($customer->name); ?> </td>
                </tr>

                <tr>
                    <th>Erstellt:</th>
                    <td><?= $suggestion->created_at; ?> </td>
                </tr>

                <tr>
                    <th>Status:</th>
                    <td>
                        <?php if (null !== $suggestion->accepted_at): ?>
                            Akzeptiert am <?= $suggestion->accepted_at ?>
                        <?php elseif (null !== $suggestion->declined_at): ?>
                            Abgelehnt am <?= $suggestion->declined_at ?>
                        <?php else: ?>
                            Neu
                        <?php endif; ?>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div><!-- /container -->

<script src="/public/vendor/lightbox-2/js/lightbox-plus-jquery.min.js"></script>

<?php Controller::partial('partials/footer'); ?>
