<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Kategorie bearbeiten',
        ]
); ?>

<h1 class="page-title pb-5">
    <?php if (isset($id)): ?>
        Kategorie bearbeiten
    <?php else : ?>
        Kategorie hinzufügen
    <?php endif; ?>
</h1>

<div class="container">
    <form method="post"
            <?php if (isset($id)): ?>
                action="/categories/edit/?id=<?= $id; ?>"
            <?php else: ?>
                action="/categories/store"
            <?php endif; ?>
          style="max-width: 500px">
        <?php if (isset($id)): ?>
            <input type="hidden" name="id" value="<?= $id; ?>">
        <?php endif; ?>
        <div>
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-control <?= isset($errors['name']) ? 'is-invalid' : ''; ?>"
                   value="<?= $old['name'] ?? ''; ?>"
                   required>
            <?php if (isset($errors['name'])) : ?>
                <div class="invalid-feedback"><?= $errors['name']; ?></div>
            <?php endif; ?>
        </div>
        <div>
            <button id="submitCategory"
                    class="btn btn-primary mt-4">
                <?php if (isset($id)): ?>
                    Kategorie speichern
                <?php else : ?>
                    Kategorie anlegen
                <?php endif; ?>
            </button>
        </div>
    </form>
</div>

<?php Controller::partial('partials/footer'); ?>
