<?php
/** @var Feature $feature */

use App\Models\Feature;

?>

<div class="card rounded-0 border-0 shadow-sm p-4 d-flex flex-column flex-md-row flex-md-wrap align-items-start align-items-md-center mb-4">
    <div class="flex-grow-1">
        <h2 class="feature-name text-body fw-bold m-0 pb-0">
            <?= $feature->getName() ?>
        </h2>
    </div>
    <div class="mt-2 w-100 border-top pt-3">
        <div class="mb-3">
            <p>
                <?= nl2br($feature->getDescription() ?? '') ?>
            </p>
        </div>
        <?php if (isset($featureVotings[$feature->id]->comment)) : ?>
            <div class="border p-3 bg-light">
                <p><strong>Ihr Kommentar:</strong></p>
                <p class="mb-0">
                    <?= htmlentities($featureVotings[$feature->id]->comment) ?>
                </p>
            </div>
        <?php endif; ?>
        <?php if (isset($feature->voting_at)): ?>
            <div class="mt-4 text-secondary small d-flex justify-content-end align-items-center">
                <div class="flex-grow-1 pe-2 text-end">
                    Abgestimmt am <?= formatDate($feature->voting_at, 'd.m.Y H:i'); ?>
                </div>
                <div class="flex-grow-0">
                    <?php if (1 === $feature->voting) : ?>
                        <div class="py-1 px-2 rounded-1 btn-ismael">
                            <i class="bi bi-hand-thumbs-up-fill"></i>
                        </div>
                    <?php endif; ?>
                    <?php if (0 === $feature->voting) : ?>
                        <div class="py-1 px-2 rounded-1 btn-ismael-red">
                            <i class="bi bi-hand-thumbs-down-fill"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4 mt-lg-3">
        <?php if (true === ($vote ?? false)) : ?>
            <div class="order-2 order-md-1 mb-3 mb-md-0">
                <a
                        class="btn btn-sm btn-ismael-red"
                        href="/vote/confirmation?id=<?= $feature->id ?>&choice=0"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        data-bs-title="Dieses Feature ist mir weniger wichtig"
                >
                    <i class="bi bi-hand-thumbs-down"></i>
                </a>
                <a
                        class="btn btn-sm btn-ismael ms-2"
                        href="/vote/confirmation?id=<?= $feature->id ?>&choice=1"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        data-bs-title="Dieses Feature ist mir wichtig"
                        data-toggle="tooltip"
                        data-
                >
                    <i class="bi bi-hand-thumbs-up"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
