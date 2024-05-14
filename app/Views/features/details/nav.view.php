<ul class="nav nav-tabs mt-4 mb-3">
    <li class="nav-item">
        <a class="nav-link <?php
        if (str_starts_with($_SERVER['REQUEST_URI'], '/features/info')): ?>active<?php
        endif; ?>" aria-current="page" href="/features/info?id=<?= $feature->id ?>">Info</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php
        if (str_starts_with($_SERVER['REQUEST_URI'], '/features/comments')): ?>active<?php
        endif; ?>" aria-current="page" href="/features/comments?id=<?= $feature->id ?>">Kommentare</a>
    </li>
</ul>
