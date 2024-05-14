<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Zugriff verweigert',
        ]
); ?>

<div class="container bg-white p-5">
    <h1>Ooops! Da ist wohl etwas schief gelaufen.</h1>

    <div class="alert alert-warning mt-4 pb-3">
        <p>
            Sie verfügen nicht über die benötigten Zugriffsrechte für diese Aktion.
        </p>
        <p>
            Klicken Sie <a href="/dashboard">hier</a>, um zum Dashboard zurückzukehren.<br>
        </p>
    </div>
</div>

<?php Controller::partial('partials/footer'); ?>
