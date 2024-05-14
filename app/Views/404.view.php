<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => '404 - Seite nicht gefunden',
        ]
); ?>

<div class="container bg-white p-5">
    <h1>Ooops! Da ist wohl etwas schief gelaufen.</h1>

    <div class="alert alert-warning mt-4 pb-3">
        <p>
            Die gesuchte Seite konnte leider nicht gefunden werden.
        </p>
        <p>
            Falls dieser Fehler erneut auftritt, melden Sie sich bitte bei uns.<br>
            <br>
            &raquo; <a class="text-body" href="https://ismael-murad.com">Meine Kontaktdaten</a>
        </p>
    </div>
</div>

<?php Controller::partial('partials/footer'); ?>
