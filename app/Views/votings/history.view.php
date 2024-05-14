<?php
/** @var Feature[] $features */

/** @var FeatureVoting[] $featureVotings */

use App\Controllers\Controller;
use App\Models\Feature;
use App\Models\FeatureVoting;

Controller::partial(
        'partials/head',
        [
                'title' => 'Ihre Stimme zählt',
        ]
);
?>
<div class="container">
    <h1 class="page-title">Ihre Stimmen</h1>

    <div class="pt-4 pb-2 text-lg-center col-10 offset-1 text-body">
        <p>
            Hier finden Sie alle Features, für die Sie bereits abgestimmt haben. Es ist nicht möglich eine abgegebene
            Stimme nachträglich zu ändern. Sollten Sie dies in Ausnahmefällen wünschen, wenden Sie sich gerne an unseren
            Support unter <a
                    href="mailto:job@ismael-murad.com">job@ismael-murad.com</a>.
        </p>
        <p>
            Vielen Dank für Ihre Teilnahme!
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
                                'vote' => false,
                                'featureVotings' => $featureVotings,
                        ]
                ); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white p-4">
                <div class="alert alert-warning mb-0">
                    <p>
                        <strong>Sie haben bisher noch nicht abgestimmt.</strong>
                    </p>
                    <p>
                        Wir würden uns natürlich sehr darüber freuen, wenn Sie aktiv an der Abstimmung teilnehmen.<br>
                        Sie helfen uns damit, die Wünsche unserer Kunden noch besser zu verstehen.
                    </p>
                    <p class="pb-0 mb-0">
                        <a href="/vote" class="btn btn-ismael mt-2">Jetzt abstimmen</a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
<?php Controller::partial('partials/footer'); ?>

