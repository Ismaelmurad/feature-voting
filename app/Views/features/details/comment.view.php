<?php if (0 !== count($votings)): ?>
    <?php
    foreach ($votings as $voting) : ?>
        <div class="voting-comment mb-4 border">
            <div class="d-flex p-2 border-bottom">
                <div class="">
                    <?php if (1 === $voting->value) : ?>
                        <i class="bi bi-hand-thumbs-up btn btn-sm btn-ismael"></i>
                    <?php else : ?>
                        <i class="bi bi-hand-thumbs-down btn btn-sm btn-danger"></i>
                    <?php endif; ?>
                </div>
                <div class="ms-md-2">
                    <?= $customers[$voting->customer_id]->name ?>
                </div>
            </div>
            <div class="p-2">
                <p><?= htmlentities($voting->comment) ?></p>
            </div>
            <div class="p-2 border-top">
                <small class="text-muted">abgestimmt am <?= formatDate($voting->created_at); ?></small>
            </div>
        </div>
    <?php
    endforeach; ?>
<?php else: ?>

    <div class="pt-3 pb-4 text-center">
        <p>
            <strong>Für dieses Feature gibt es noch keine Kommentare.</strong>
        </p>
    </div>

<?php endif; ?>
