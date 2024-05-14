<?php

use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => $feature->name . '- Features',
        ]
); ?>
<div class="container">
    <h1 class="page-title pb-5">Feature: <?= htmlentities($feature->name); ?></h1>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6 offset-md-1 offset-lg-2 offset-xl-3 bg-white p-3">
            <?php
            Controller::partial(
                    '/features/details/nav',
                    [
                            'feature' => $feature,
                    ]
            ); ?>
            <table class="table table-bordered w-100">
                <thead>
                <tbody>
                <tr>
                    <th>ID:</th>
                    <td> <?= $feature->id; ?> </td>
                </tr>

                <tr>
                    <th>Name:</th>
                    <td><?= htmlentities($feature->name); ?> </td>
                </tr>

                <tr>
                    <th>Kategorie:</th>
                    <td><?= $category ?? '' ?></td>
                </tr>

                <tr>
                    <th>Beschreibung:</th>
                    <td><?= htmlentities($feature->description); ?> </td>
                </tr>

                <tr>
                    <th>Stimmen dafür:</th>
                    <td><?= $feature->votes_up; ?> </td>
                </tr>

                <tr>
                    <th>Stimmen dagegen:</th>
                    <td><?= $feature->votes_down; ?> </td>
                </tr>

                <tr>
                    <th>Stimmen gesamt:</th>
                    <td><?= $feature->votes_total; ?> </td>
                </tr>

                <tr>
                    <th>Score:</th>
                    <td><?= $feature->score; ?> </td>
                </tr>

                <tr>
                    <th>Erstellt:</th>
                    <td><?= $feature->created_at; ?> </td>
                </tr>

                <tr>
                    <th>Aktualisiert:</th>
                    <td><?= $feature->updated_at; ?> </td>
                </tr>

                <tr>
                    <th>Abstimmung endet:</th>
                    <td><?= $feature->voting_ends_at; ?> </td>
                </tr>

                <tr>
                    <th>Zuletzt besucht:</th>
                    <td><?= $feature->last_visit_at; ?> </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div><!-- /container -->

<?php
Controller::partial('partials/footer'); ?>
