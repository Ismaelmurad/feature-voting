<?php
/** @var Suggestion[] $items */
/** @var Customer[] $customers */
/** @var int $totalPages */
/** @var int $page */

use App\Controllers\Controller;
use App\Models\Customer;
use App\Models\Suggestion;

Controller::partial(
        'partials/head',
        [
                'title' => 'Vorschläge',
        ]
); ?>

<div class="container">
    <h1 class="page-title pb-5">Vorschläge</h1>

    <form action="/suggestions"
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
            <label class="mb-2" for="status">Status</label>
            <select class="form-select border-0" id="status" name="status">
                <option <?php
                        if ($old['status'] === null): ?>selected<?php
                endif; ?> value="">Alle
                </option>
                <option <?php
                        if ($old['status'] === '2'): ?>selected<?php
                endif; ?> value="2">Neu
                </option>
                <option <?php
                        if ($old['status'] === '1'): ?>selected<?php
                endif; ?> value="1">Akzeptiert
                </option>
                <option <?php
                        if ($old['status'] === '0'): ?>selected<?php
                endif; ?> value="0">Abgelehnt
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
                                'name' => 'Titel',
                                'field' => 'name',
                        ],
                        [
                                'name' => 'Kunde',
                                'field' => 'customer_id',
                        ],
                        [
                                'name' => 'Eingereicht am',
                                'field' => 'created_at',
                        ],
                        [
                                'name' => 'Akzeptiert am',
                                'field' => 'accepted_at',
                        ],
                        [
                                'name' => 'Abgelehnt am',
                                'field' => 'declined_at',
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
                Controller::partial(
                        'partials/itemsNotFound',
                        [
                                'item' => 'Vorschläge'
                        ]
                );
            endif; ?>

            <?php
            foreach ($items as $item) :
                if (null === $item->accepted_at) {
                    $detailLink = '/suggestions/info?id=' . $item->id;
                } else {
                    $detailLink = '/features/info?id=' . $item->feature_id;
                }
                ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><?= htmlentities($item->name) ?></td>
                    <td><?= $customers[$item->customer_id]->name ?? '<i>Gelöscht</i>' ?></td>
                    <td><?= formatDate($item->created_at, 'd.m.Y H:i') ?></td>
                    <td><?= formatDate($item->accepted_at, 'd.m.Y H:i') ?></td>
                    <td><?= formatDate($item->declined_at, 'd.m.Y H:i') ?></td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-sm btn-ismael mx-1"
                               href="<?= $detailLink; ?>"
                            >
                                Details
                            </a>
                            <a class="btn btn-sm btn-ismael mx-1 <?php if (null !== $item->declined_at || null !== $item->accepted_at) : ?>disabled text-white<?php endif; ?>"
                               href="/suggestions/accept?id=<?= $item->id ?>"
                            >
                                Akzeptieren
                            </a>
                            <a class="btn btn-sm btn-danger btn-delete mx-1 <?php if (null !== $item->accepted_at || null !== $item->declined_at) : ?>disabled<?php endif; ?>"
                               href="/suggestions/decline?id=<?= $item->id ?>"
                               data-name="<?= $item->name ?>"
                            >
                                Ablehnen
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
Controller::partial('partials/footer'); ?>
