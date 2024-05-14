<?php
/** @var Feature[] $features */

use App\Controllers\Controller;
use App\Models\Feature;

Controller::partial(
        'partials/head',
        [
                'title' => 'Ihre Stimme zählt',
        ]
);
?>
<div class="container">
    <h1 class="page-title">Feature Voting</h1>

    <div class="pt-4 pb-2 text-lg-center col-10 offset-1 text-body">
        <p>
            Stimmen Sie ab und wirken Sie so direkt an der Zukunftsgestaltung unserer Software mit. Sagen Sie uns welche
            Funktionen für Sie wichtig oder eher unwichtig sind.

            Um Ihre Meinung nicht zu beeinflussen, sind die angezeigten Features in einer zufälligen Reihenfolge
            sortiert.
        </p>
        <p>
            Für jedes Feature haben Sie eine Stimme!
        </p>
    </div>

    <hr class="text-secondary mb-2">

    <?php Controller::partial(
            'votings/partials/tabs',
            []
    ); ?>

    <div class="mt-4">
        <?php if (0 !== count($features)): ?>
            <?php foreach ($features as $index => $feature) : ?>
                <?php Controller::partial(
                        'votings/feature',
                        [
                                'index' => $index,
                                'feature' => $feature,
                                'vote' => true,
                        ]
                ); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="pt-3 pb-4">
                <p>
                    Es gibt aktuell keine neuen Funktionen für die Abstimmung.
                </p>
                <p class="pb-0 mb-0">
                    Schlagen Sie mir <a href="https://ismael-murad.com" target="_blank">hier</a> gerne eine neue
                    Funktion vor.
                </p>
            </div>
        <?php endif; ?>
    </div>

</div>
<?php Controller::partial('partials/footer'); ?>
