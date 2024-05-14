<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Vorschlag erfolgreich',
        ]
); ?>

<div class="container">
    <h1 class="page-title">Vorschlag gespeichert.</h1>

    <hr class="text-secondary mb-2">

    <?php Controller::partial(
            'votings/partials/tabs',
            []
    ); ?>

    <div class="mt-4 alert alert-success shadow-sm rounded-0">
        <h2 class="mb-4">Vielen Dank!</h2>
        <p>
            Wir haben Ihren Vorschlag erhalten. Schön, dass Sie an unserer Zukunftsplanung mitwirken.
        </p>
        <p class="small mb-0">
            Klicken Sie <a class="text-body" href="/vote/suggestion">hier</a>, um einen weiteren Vorschlag einzureichen.
        </p>
    </div>
</div>

<?php Controller::partial('partials/footer'); ?>
