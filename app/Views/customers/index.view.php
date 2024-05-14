<?php
/** @var Customer[] $customers */

use App\Controllers\Controller;
use App\Models\Customer;

Controller::partial(
        'partials/head',
        [
                'title' => 'Kunden',
        ]
); ?>

<div class="container">
    <h1 class="page-title pb-5">Kunden</h1>

    <?php if (isset($mailedCustomer)): ?>
        <div class="alert alert-success mt-4 pb-3">
            E-Mail erfolgreich an <strong><?= $mailedCustomer->name ?></strong> gesendet.
        </div>
    <?php endif; ?>

    <form action="/customers"
          method="get"
          class="row g-3 align-items-center">
        <input type="hidden" name="perPage" value="<?= $old['perPage'] ?? ''; ?>">
        <div class="col-12 col-md-3">
            <label for="name" class="mb-2">Name</label>
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="z.B. &quot;Müller&quot;"
                   value="<?= $old['name'] ?? ''; ?>"
            >
        </div>
        <div class="col-12 col-md-2">
            <label class="mb-2" for="has_votes">Abstimmung</label>
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
            <label class="mb-2" for="has_visited">Link geöffnet</label>
            <select class="form-select border-0" id="has_visited" name="has_visited">
                <option <?php
                        if (($old['has_visited'] ?? null) === ""): ?>selected<?php
                endif; ?> value="">alle
                </option>
                <option <?php
                        if (($old['has_visited'] ?? null) === "1"): ?>selected<?php
                endif; ?> value="1">Bereits geöffnet
                </option>
                <option <?php
                        if (($old['has_visited'] ?? null) === "0"): ?>selected<?php
                endif; ?> value="0">Nicht geöffnet
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
                    onclick="location.href='/customers/create';">
                <i class="bi-plus"></i> Kunde hinzufügen
            </button>
        </div>
    </form>

    <!-- Table -->

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
                                'name' => 'Stimmen',
                                'field' => 'votes_total',
                        ],
                        [
                                'name' => 'angelegt am',
                                'field' => 'created_at',
                        ],
                        [
                                'name' => 'E-Mail gesendet am',
                                'field' => 'email_sent_at',
                        ],
                        [
                                'name' => '',
                                'field' => '',
                                'width' => '365px',
                        ],
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

            <?php if (0 === count($customers)) :
                Controller::partial('partials/itemsNotFound', ['item' => 'Kunden']);
            endif; ?>

            <?php

            foreach ($customers as $customer) :?>
                <tr>
                    <td><?= $customer->getId() ?></td>
                    <td><?= htmlentities($customer->getName()) ?></td>
                    <td><?= $customer->getVotesUp() ?></td>
                    <td><?= $customer->getVotesDown() ?></td>
                    <td><?= $customer->getVotesTotal() ?></td>
                    <td><?= formatDate($customer->getCreatedAt(), 'd.m.Y H:i') ?></td>
                    <td><?= formatDate($customer->getEmailSentAt(), 'd.m.Y H:i') ?></td>
                    <td>
                        <a class="btn btn-sm btn-ismael"
                           href="/customers/show?id=<?= $customer->getId() ?>"
                        >
                            Details
                        </a>

                        <a class="btn btn-sm btn-ismael <?php if (null === $customer->getEmail()) : ?>disabled<?php endif; ?>"
                           href="/customers/mail?id=<?= $customer->getId() ?>"
                        >
                            E-Mail versenden
                        </a>

                        <a class="btn btn-sm btn-ismael btn-edit"
                           href="/customers/edit?id=<?= $customer->getId() ?>"
                           data-name="<?= $customer->getName() ?>"
                        >
                            Bearbeiten
                        </a>

                        <a class="btn btn-sm btn-danger btn-delete"
                           href="/customers/delete?id=<?= $customer->getId() ?>"
                           data-name="<?= $customer->getName() ?>"
                        >
                            Löschen
                        </a>
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
                'title' => 'Kunde löschen',
                'message' => 'Wollen Sie den Kunden "{name}" wirklich löschen?',
        ]
); ?>
<?php
Controller::partial('partials/footer'); ?>
