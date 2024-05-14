<div class="bg-white shadow-sm mt-4 px-4 py-3">
    <a class="btn <?php if (str_contains(basename($_SERVER['REQUEST_URI']), 'vote') || str_contains(basename($_SERVER['REQUEST_URI']), 'latest')) : ?>btn-ismael<?php endif; ?>"
       href="/vote"> Neu
    </a>

    <a class="btn <?php if (str_contains(basename($_SERVER['REQUEST_URI']), 'history')) : ?>btn-ismael<?php endif; ?>"
       aria-current="page"
       href="/vote/history">
        Abgestimmt
    </a>

    <a class="btn <?php if (str_contains(basename($_SERVER['REQUEST_URI']), 'suggestion') || str_contains(basename($_SERVER['REQUEST_URI']), 'submit')) : ?>btn-ismael<?php endif; ?>"
       aria-current="page"
       href="/vote/suggestion">
        Vorschlagen
    </a>
</div>
