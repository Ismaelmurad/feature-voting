<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Kunde bearbeiten',
        ]
); ?>

<h1 class="page-title pb-5">
    <?php
    if (isset($id)): ?>
        Kunde bearbeiten
    <?php
    else : ?>
        Kunde hinzufügen
    <?php
    endif; ?>
</h1>

<div class="container">
    <form method="post"
          class="col-12 col-md-10 col-lg-8 offset-md-1 offset-lg-2"
          <?php if (isset($id)): ?>action="/customers/edit/?id=<?= $id; ?>"
          <?php else: ?>action="/customers/store"<?php endif; ?>
    >
        <?php if (isset($id)): ?>
            <input type="hidden" name="id" value="<?= $id; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-12 col-md-6">

                <div class="mb-3">
                    <label for="name" class="mb-2">Firmenname*</label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control border-0 <?= isset($errors['name']) ? 'is-invalid' : ''; ?>"
                           value="<?= $old['name'] ?? ''; ?>"
                           required
                    >
                    <?php if (isset($errors['name'])) : ?>
                        <div class="invalid-feedback"><?= $errors['name']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="contact_person" class="mb-2">Ansprechspartner</label>
                    <input type="text"
                           name="contact_person"
                           class="form-control border-0"
                           id="contact_person"
                           value="<?= $old['contact_person'] ?? ''; ?>"
                    >
                </div>
                <div class="mb-3">
                    <label for="amount_staff" class="mb-2">Anzahl Mitarbeiter</label>
                    <input type="text"
                           name="amount_staff"
                           class="form-control border-0"
                           id="amount_staff"
                           value="<?= $old['amount_staff'] ?? ''; ?>"
                    >
                </div>
                <div class="mb-3">
                    <label for="monthly_sales" class="mb-2">Monatlicher Umsatz</label>
                    <input type="text"
                           name="monthly_sales"
                           class="form-control border-0"
                           id="monthly_sales"
                           value="<?= $old['monthly_sales'] ?? ''; ?>"
                    >
                </div>

            </div>
            <div class="col-12 col-md-6">
                <div class="mb-3">
                    <label for="phone" class="mb-2">Telefon</label>
                    <input type="text"
                           name="phone"
                           class="form-control border-0 <?= isset($errors['phone']) ? 'is-invalid' : ''; ?>"
                           value="<?= $old['phone'] ?? ''; ?>"
                           id="phone">
                    <?php
                    if (isset($errors['phone'])) : ?>
                        <div class="invalid-feedback"><?= $errors['phone']; ?></div>
                    <?php
                    endif; ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="mb-2">E-Mail</label>
                    <input type="text"
                           name="email"
                           class="form-control border-0 <?= isset($errors['email']) ? 'is-invalid' : ''; ?>"
                           value="<?= $old['email'] ?? ''; ?>"
                           id="email">
                    <?php
                    if (isset($errors['email'])) : ?>
                        <div class="invalid-feedback"><?= $errors['email']; ?></div>
                    <?php
                    endif; ?>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-ismael mt-3" name="submit">
                <?php
                if (isset($id)): ?>
                    Änderungen speichern
                <?php
                else: ?>
                    Kunde anlegen
                <?php
                endif; ?>
            </button>
        </div>
    </form>
</div>

<?php Controller::partial('partials/footer'); ?>
