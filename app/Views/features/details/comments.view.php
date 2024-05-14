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

            <?php
            Controller::partial(
                    'features/details/comment',
                    [
                            'votings' => $votings,
                            'customers' => $customers,
                    ]
            ); ?>

            <?php
            Controller::partial(
                    'partials/pagination',
                    [
                            'totalPages' => $totalPages,
                            'page' => $page,
                            'old' => $old,
                    ]
            ); ?>


        </div>
    </div>
</div>


<?php
Controller::partial('partials/footer'); ?>

