<div class="d-flex flex-row justify-content-start">
    <?php if ($totalPages > 1) : ?>
        <ul class="pagination justify-content-start flex-grow-1">
            <?php if ($page > 1): ?>
                <?php
                $params = $old ?? [];
                $params['page'] = isset($params['page']) ? $params['page'] - 1 : 1;
                $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?' . http_build_query($params);
                ?>
                <li class="page-item">
                    <a class="page-link text-ismael" href="<?= $url; ?>">&#171;</a>
                </li>
            <?php endif; ?>
            <?php

            for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php
                $params = $old ?? [];
                $params['page'] = $i;
                $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?' . http_build_query($params);
                ?>
                <li class="page-item">
                    <a class="page-link <?php if ($page === $params['page']) : ?>bg-ismael text-white<?php else: ?>text-ismael<?php endif; ?>"
                       href="<?= $url; ?>"><?= $i; ?></a>
                </li>
            <?php
            endfor; ?>
            <?php if ($page < $totalPages): ?>
                <?php
                $params = $old ?? [];
                $params['page'] = isset($params['page']) ? $params['page'] + 1 : 1;
                $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?' . http_build_query($params);
                ?>
                <li class="page-item">
                    <a class="page-link text-ismael" href="<?= $url; ?>">&#187;</a>
                </li>
            <?php endif; ?>
        </ul>

        <?php
        $params = $old ?? ['page' => 1, 'perPage', 15];
        $perPageValues = [
                5,
                15,
                30,
                50,
        ];
        ?>
        <div class="dropdown me-3">
            <button class="btn btn-outline-ismael dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <?= $old['perPage'] ?? 15; ?>
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($perPageValues as $value): ?>
                    <?php
                    $params['page'] = 1;
                    $params['perPage'] = $value;
                    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?' . http_build_query($params);
                    ?>
                    <li>
                        <a class="dropdown-item"
                           href="<?= $url; ?>">
                            <?= $value; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="flex-grow-1"></div>
    <?php endif; ?>
</div>
