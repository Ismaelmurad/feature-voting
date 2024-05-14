<?php

use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Abstimmung beendet',
        ]
); ?>
<div class="container">
    <h1 class="page-title"><?= $feature->name ?></h1>

    <div class="card p-4 mt-5">
        <div class="alert alert-warning mb-0">
            <p>
                <strong>
                    Die Abstimmung für dieses Feature ist leider schon beendet.
                </strong>
            </p>
            <p>
                Wir freuen uns natürlich, wenn Sie noch für weitere Features abstimmen möchten.<br>
                <a class="text-body" href="/vote/latest">Hier</a> finden Sie alle Vorschläge.
            </p>
        </div>
    </div>
</div>
<?php
Controller::partial('partials/footer'); ?>
