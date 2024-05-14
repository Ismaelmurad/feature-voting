<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'E-Mail konnte nicht gesendet werden',
        ]
); ?>

<div class="container bg-white p-5">
    <h1>Ooops! Da ist wohl etwas schief gelaufen.</h1>

    <div class="alert alert-danger mt-4 pb-3">
        <p>
            <strong>Die E-Mail konnte leider nicht gesendet werden.</strong>

        <p>
            Kunde: <?= $customer->name ?>
            <br>
            ID: <?= $customer->id ?>
            <br>
            Fehlercode: <?= $errors['code'] ?>
            <br>
            Fehlermeldung: <?= $errors['message'] ?>
        </p>
    </div>
</div>

<?php Controller::partial('partials/footer'); ?>
