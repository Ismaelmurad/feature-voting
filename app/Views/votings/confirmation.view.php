<?php

use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Ihre Stimme zählt',
        ]
); ?>
<div class="container">
    <h1 class="page-title"><?= $feature->name ?></h1>

    <div class="card mt-5 mb-4 p-4">
        <div class="form-group">
            <form action="/vote/submit" method="post">
                <label for="comment">
                    <?php
                    if ($choice === 1): ?>
                        Lassen Sie uns <strong>optional</strong> wissen, warum Ihnen dieses Feature wichtig ist.
                    <?php
                    else: ?>
                        Lassen Sie uns <strong>optional</strong> wissen, warum Ihnen dieses Feature weniger wichtig ist.
                    <?php
                    endif; ?>
                </label>
                <textarea class="form-control mt-4" id="comment" rows="3" name="comment"></textarea>

                <input type="hidden" name="id" value="<?= $feature->id ?>">
                <input type="hidden" name="choice" value="<?= $choice ?>">

                <div class="mt-4">
                    <button class="btn btn-ismael me-2"
                            type="submit"
                            id="submit">
                        Abstimmen
                    </button>

                    <a class="btn btn-danger"
                       id="cancel"
                       href="/vote/latest">
                        Abbrechen
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card p-4 mt-3">
        <p>
            Ihre Stimme:&nbsp;
            <?php
            if ($choice === 1): ?>
                <i class="bi bi-hand-thumbs-up btn btn-sm btn-ismael"></i>
            <?php
            else: ?>
                <i class="bi bi-hand-thumbs-down btn btn-sm btn-danger"></i>
            <?php
            endif; ?>
        </p>
        <p>
            <strong>Beschreibung:</strong>
        </p>
        <p class="mb-0 mb-md-2">
            <?= $feature->description ?>
        </p>
    </div>
</div>
<?php
Controller::partial('partials/footer'); ?>
