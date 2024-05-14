<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Übersicht',
        ]
); ?>

<div class="container">
    <h1 class="page-title pb-5">Übersicht</h1>

    <div class="row mt-4">
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Kunden</div>
                <div class="card-body">
                    <p class="card-text text-center"><?= $amountCustomers; ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Features</div>
                <div class="card-body">
                    <p class="card-text text-center"><?= $amountFeatures; ?></p>
                </div>
            </div>
        </div>
        <div></div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Stimmen</div>
                <div class="card-body">
                    <p class="card-text text-center"><?= $amountVotes; ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">(+) Stimmen</div>
                <div class="card-body">
                    <p class="card-text text-center"><?= $amountPositive; ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">(-) Stimmen</div>
                <div class="card-body">
                    <p class="card-text text-center"><?= $amountNegative; ?></p>
                </div>
            </div>
        </div>
        <div></div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Kunden mit einer Stimme oder mehr</div>
                <div class="card-body">
                    <p class="card-text text-center"><?= $amountCustomersWhoVoted; ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Erste Besuche</div>
                <div class="card-body">
                    <p class="card-text text-center"><?= $amountCustomersVisitedEver; ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Kunden eingeloggt in den letzten 30 Tagen</div>
                <div class="card-body">
                    <p class="card-text text-center"><?= $amountCustomersVisitedLastMonth; ?></p>
                </div>
            </div>
        </div>
    </div>
</div><!-- /container -->

<?php Controller::partial('partials/footer'); ?>
