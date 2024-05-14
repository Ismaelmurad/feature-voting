<?php use App\Controllers\Controller;

Controller::partial('partials/head',
        [
                'title' => 'Feature bearbeiten',
        ]
); ?>

<h1 class="page-title pb-5">
    <?php
    if (isset($id)): ?>
        Feature bearbeiten
    <?php
    else : ?>
        Feature hinzufügen
    <?php
    endif; ?>
</h1>

<div class="container">
    <form method="post"
          class="col-12 col-md-10 col-lg-8 offset-md-1 offset-lg-2"
          <?php if (isset($id)): ?>action="/features/edit/?id=<?= $id; ?>"
          <?php else: ?>action="/features/store"<?php endif; ?>
    >
        <?php if (isset($id)): ?>
            <input type="hidden" name="id" value="<?= $id; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-12 col-md-6">

                <div class="mb-3">
                    <label for="name" class="mb-2">Name*</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control border-0 <?= isset($errors['name']) ? 'is-invalid' : ''; ?>"
                           value="<?= $old['name'] ?? ''; ?>"
                           required
                    >
                    <?php
                    if (isset($errors['name'])) : ?>
                        <div class="invalid-feedback"><?= $errors['name']; ?></div>
                    <?php
                    endif; ?>
                </div>

                <div class="mb-3">
                    <label for="voting_ends_at" class="mb-2">Voting möglich bis</label>
                    <input type="date"
                           name="voting_ends_at"
                           class="form-control border-0"
                           id="voting_ends_at"
                           value="<?= $old['voting_ends_at'] ?? null; ?>"
                    >
                </div>

            </div>

            <div class="col-12 col-md-6">

                <div class="mb-3">
                    <label for="feature_category_id" class="mb-2">Kategorie*</label>
                    <select name="feature_category_id" class="form-select border-0" id="feature_category_id" required>
                        <option selected>Kategorie auswählen</option>
                        <?php
                        foreach ($feature_categories as $category) : ?>
                            <option
                                    value="<?= $category->id; ?>"
                                    <?php if ($old['feature_category_id'] ?? null === $category->id) : ?>
                                        selected="selected"
                                    <?php endif; ?>
                            ><?= $category->name; ?></option>
                        <?php
                        endforeach; ?>
                    </select>
                </div>

            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="mb-2">Beschreibung</label>
            <textarea
                    class="form-control border-0"
                    id="description"
                    name="description"
                    rows="10"
            ><?= $old['description'] ?? null; ?></textarea>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-ismael border-0 mt-3" name="submit">
                <?php
                if (isset($id)): ?>
                    Feature speichern
                <?php
                else: ?>
                    Feature anlegen
                <?php
                endif; ?>
            </button>
        </div>


    </form>
</div>

<?php Controller::partial('partials/footer'); ?>
