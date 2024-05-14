<?php

use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Features',
        ]
); ?>

<div class="container">
    <h1 class="page-title pb-5">Feature Voting</h1>

    <form action="/features"
          method="get"
          class="row g-3 align-items-center">
        <input type="hidden" name="perPage" value="<?= $old['perPage'] ?? ''; ?>">
        <div class="col-12 col-md-3">
            <label for="name" class="mb-2">Suchbegriff</label>
            <input type="text"
                   class="form-control border-0"
                   id="name"
                   name="name"
                   placeholder="z.B. &quot;Bildergalerie&quot;"
                   value="<?= $old['name'] ?? ''; ?>"
            >
        </div>
        <div class="col-12 col-md-2">
            <label class="mb-2" for="has_votes">Status</label>
            <select class="form-select border-0" id="has_votes" name="has_votes">
                <option <?php
                        if (($old['has_votes'] ?? null) === ""): ?>selected<?php
                endif; ?> value="">alle
                </option>
                <option <?php
                        if (($old['has_votes'] ?? null) === "1"): ?>selected<?php
                endif; ?> value="1">Bereits abgestimmt
                </option>
                <option <?php
                        if (($old['has_votes'] ?? null) === "0"): ?>selected<?php
                endif; ?> value="0">Nicht abgestimmt
                </option>
            </select>
        </div>

        <div class="col-12 col-md-2">
            <label class="mb-2" for="has_comment">Kommentare</label>
            <select class="form-select border-0" id="has_comment" name="has_comment">
                <option <?php
                        if (($old['has_comment'] ?? null) === ""): ?>selected<?php
                endif; ?> value="">alle
                </option>
                <option <?php
                        if (($old['has_comment'] ?? null) === "1"): ?>selected<?php
                endif; ?> value="1">Hat Kommentar
                </option>
                <option <?php
                        if (($old['has_comment'] ?? null) === "0"): ?>selected<?php
                endif; ?> value="0">Hat keinen Kommentar
                </option>
            </select>
        </div>

        <div class="col-12 col-md-2 flex-md-grow-1">
            <label class="mb-2 d-block">&nbsp;</label>
            <button type="submit"
                    class="btn btn-ismael border-0">
                Suchen
            </button>
        </div>
        <div class="col-12 col-md-3 text-end">
            <label class="mb-2 d-block">&nbsp;</label>
            <button type="button"
                    class="btn btn-ismael border-0"
                    onclick="location.href='/features/create';">
                <i class="bi-plus"></i> Feature hinzufügen
            </button>
        </div>
    </form>

    <div class="bg-white p-3 mt-5">
        <table class="table table-ismael bg-white">
            <thead class="thead-dark">
            <tr>
                <?php
                $headers = [
                        [
                                'name' => 'ID',
                                'field' => 'id',
                        ],
                        [
                                'name' => 'Feature Titel',
                                'field' => 'name',
                        ],
                        [
                                'name' => '<i class="bi bi-hand-thumbs-up-fill"></i>',
                                'field' => 'votes_up',
                                'title' => 'Positive Stimmen',
                        ],
                        [
                                'name' => '<i class="bi bi-hand-thumbs-down-fill"></i>',
                                'field' => 'votes_down',
                                'title' => 'Negative Stimmen',
                        ],
                        [
                                'name' => '<i class="bi bi-graph-up"></i>',
                                'field' => 'score',
                                'title' => 'Score',
                        ],
                        [
                                'name' => 'angelegt am',
                                'field' => 'created_at',
                        ],
                        [
                                'name' => 'Voting bis',
                                'field' => 'voting_ends_at',
                        ],
                        [
                                'name' => '',
                                'field' => '',
                                'width' => '240px',
                        ]
                ];
                $params = $old;
                ?>
                <?php
                foreach ($headers as $header): ?>
                    <?php
                    $params['orderBy'] = $header['field'];
                    $params['direction'] = 'ASC';
                    $ascUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?' . http_build_query($params);
                    $params['direction'] = 'DESC';
                    $descUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?' . http_build_query($params);
                    ?>
                    <th <?php
                        if (!empty($header['width'])) : ?>style="width: <?= $header['width']; ?>"<?php
                    endif; ?>>
                        <div class="col-12 <?php
                        if (($old['orderBy'] ?? '') === $header['field']) : ?>active<?php
                        endif; ?>">
                            <span <?php
                                  if (!empty($header['title'])) : ?>title="<?= $header['title']; ?>"
                                  data-bs-toggle="tooltip"<?php
                            endif; ?>>
                                <?= $header['name']; ?>
                            </span>

                            <?php
                            if (!empty($header['field'])) : ?>
                                <div class="column-order">
                                    <a href="<?= $ascUrl; ?>" <?php
                                    if (($old['orderBy'] ?? '') === $header['field'] && ($old['direction'] ?? 'ASC') === 'ASC'): ?>class="active"<?php
                                    endif; ?>>&#8595;</a>
                                    <a href="<?= $descUrl; ?>" <?php
                                    if (($old['orderBy'] ?? '') === $header['field'] && ($old['direction'] ?? 'DESC') === 'DESC'): ?>class="active"<?php
                                    endif; ?>>&#8593;</a>
                                </div>
                            <?php
                            endif; ?>
                        </div>
                    </th>
                <?php
                endforeach; ?>

            </tr>
            </thead>

            <?php if (0 === count($items)) :
                Controller::partial('partials/itemsNotFound', ['item' => 'Features']);
            endif; ?>

            <?php foreach ($items as $item) : ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><?= htmlentities($item->name) ?></td>
                    <td><?= $item->votes_up ?></td>
                    <td><?= $item->votes_down ?></td>
                    <td><?= $item->score ?></td>
                    <td><?= formatDate($item->created_at, 'd.m.Y H:i') ?></td>
                    <td><?= formatDate($item->voting_ends_at, 'd.m.Y H:i') ?? '-' ?></td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-sm btn-ismael"
                               href="/features/info?id=<?= $item->id ?>"
                            >
                                Details
                            </a>

                            <a class="btn btn-sm btn-ismael btn-edit"
                               href="/features/edit?id=<?= $item->id ?>"
                               data-name="<?= $item->name ?>"
                            >
                                Bearbeiten
                            </a>

                            <a class="btn btn-sm btn-danger btn-delete"
                               href="/features/delete?id=<?= $item->id ?>"
                               data-name="<?= $item->name ?>"
                            >
                                Löschen
                            </a>
                        </div>
                    </td>
                </tr>
            <?php
            endforeach; ?>
        </table>

        <?php
        Controller::partial(
                'partials/pagination',
                [
                        'totalPages' => $totalPages,
                        'page' => $page,
                        'old' => $old,
                ]
        ); ?>
    </div>
</div><!-- /container -->

<?php
Controller::partial(
        'partials/modal.delete',
        [
                'title' => 'Feature löschen',
                'message' => 'Wollen Sie das Feature "{name}" wirklich löschen?',
        ]
); ?>
<?php
Controller::partial('partials/footer'); ?>
