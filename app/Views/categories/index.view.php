<?php

use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Kategorien',
        ]
); ?>

<div class="container">
    <h1 class="page-title pb-5">Kategorien</h1>

    <form action="/categories"
          method="get"
          class="row g-3 align-items-center">
        <input type="hidden" name="perPage" value="<?= $old['perPage'] ?? ''; ?>">
        <div class="col-12 col-md-4">
            <label class="mb-2" for="name">Name</label>
            <input type="text"
                   class="form-control border-0"
                   id="name"
                   name="name"
                   placeholder="Bitte Suchbegriff eingeben"
                   value="<?= $old['name'] ?? ''; ?>"
            >
        </div>
        <div class="col-12 col-md-3">
            <label class="mb-2 d-block">&nbsp;</label>
            <button type="submit"
                    class="btn btn-ismael border-0">
                Suchen
            </button>
        </div>
        <div class="col-12 col-md-5 text-end">
            <label class="mb-2 d-block">&nbsp;</label>
            <button type="button"
                    class="btn btn-ismael border-0"
                    onclick="location.href='/categories/create';">
                <i class="bi-plus"></i> Kategorie hinzufügen
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
                                'name' => 'Name',
                                'field' => 'name',
                        ],
                        [
                                'name' => 'Erstellt',
                                'field' => 'created_at',
                        ],
                        [
                                'name' => 'Aktualisiert',
                                'field' => 'updated_at',
                        ],
                        [
                                'name' => '',
                                'field' => '',
                                'width' => '180px',
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
                            <?= $header['name']; ?>

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
                Controller::partial('partials/itemsNotFound', ['item' => 'Kategorien']);
            endif; ?>

            <tbody>
            <?php
            foreach ($items as $item) : ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><?= $item->name ?></td>
                    <td><?= $item->created_at ?></td>
                    <td><?= $item->updated_at ?></td>
                    <td>
                        <div class="d-flex justify-content-around">
                            <a class="btn btn-sm btn-ismael btn-edit"
                               href="/categories/edit?id=<?= $item->id ?>"
                               data-name="<?= $item->name ?>"
                            >
                                Bearbeiten
                            </a>

                            <a class="btn btn-sm btn-danger btn-delete"
                               href="/categories/delete?id=<?= $item->id ?>"
                               data-name="<?= $item->name ?>"
                            >
                                Löschen
                            </a>
                        </div>
                    </td>
                </tr>
            <?php
            endforeach; ?>
            </tbody>
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
                'title' => 'Kategorie löschen',
                'message' => 'Wollen Sie die Kategorie "{name}" wirklich löschen?',
        ]
); ?>
<?php
Controller::partial('partials/footer'); ?>
