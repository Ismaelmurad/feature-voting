<?php
/** @var Customer $customer */

use App\Controllers\Controller;
use App\Models\Customer;

Controller::partial(
        'partials/head',
        [
                'title' => 'E-Mail erneut senden?',
        ]
); ?>

<div class="container bg-white p-5">
    <h1>E-Mail erneut senden?</h1>

    <div class="alert alert-warning mt-4 pb-3">
        <p>
            Der Kunde <strong><?= $customer->getName() ?></strong> hat die E-Mail zuletzt am
            <strong><?= formatDate($customer->getEmailSentAt(), 'd.m.Y') ?>
                um <?= formatDate($customer->getEmailSentAt(), 'H:i') ?> Uhr</strong> erhalten.
        </p>
        <p>
            Soll die E-Mail erneut gesendet werden?
        </p>
        <div class="">
            <a class="btn btn-sm btn-ismael btn-edit"
               href="/customers/mail?id=<?= $customer->getId() ?>&confirm=1"
            >
                Erneut senden
            </a>

            <a class="btn btn-sm btn-danger btn-delete"
               href="/customers"
            >
                Abbrechen
            </a>
        </div>
    </div>
</div>

<?php Controller::partial('partials/footer'); ?>
