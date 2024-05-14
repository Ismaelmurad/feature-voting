<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Ihre Stimme zählt',
        ]
); ?>
<div class="container">
    <h1 class="page-title">Ihr Vorschlag</h1>

    <div class="pt-4 pb-2 text-lg-center col-10 offset-1 text-body">
        <p>
            Hier können Sie uns einen Vorschlag für ein Feature schicken, dass Sie gerne in meiner Software sehen
            würden. Wenn Ihr Vorschlag von unserem Produkt-Team entsprechend bewertet wird, wird dieser anschließend zur
            Abstimmung freigegeben.
        </p>
        <p>
            Vielen Dank für Ihr Engagement!
        </p>
    </div>

    <hr class="text-secondary mb-2">

    <?php Controller::partial(
            'votings/partials/tabs',
            []
    ); ?>

    <div class="card rounded-0 shadow-sm p-4 mt-4 border-0">
        <form method="post"
              class="col-12 col-md-10 col-lg-6 needs-validation"
              action="/vote/suggestion/submit"
              enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-12 col-md-12">

                    <div class="mb-3">
                        <label for="name" class="mb-2">Titel*</label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control border-1 <?= isset($errors['title']) ? 'is-invalid' : ''; ?>"
                               value=""
                               required
                        >
                        <?php
                        if (isset($errors['title'])):
                            foreach ($errors['title'] as $error): ?>
                                <div class="invalid-feedback d-block"><?= $error ?></div>
                            <?php endforeach;
                        endif;
                        ?>
                    </div>

                    <div class="mb-3">
                        <label for="text" class="mb-2">Beschreibung*</label>
                        <textarea
                                class="form-control border-1 <?= isset($errors['text']) ? 'is-invalid' : ''; ?>"
                                id="text"
                                name="text"
                                rows="6"
                                required
                        ></textarea>
                        <?php
                        if (isset($errors['text'])):
                            foreach ($errors['text'] as $error): ?>
                                <div class="invalid-feedback d-block"><?= $error ?></div>
                            <?php endforeach;
                        endif;
                        ?>
                    </div>

                    <div class="mb-3">
                        <label for="pictures" class="form-label">Beschreibende Bilder</label>
                        <input
                                class="form-control <?= isset($errors['file']) ? 'is-invalid' : ''; ?>"
                                type="file"
                                id="pictures"
                                name="pictures[]"
                                multiple
                        >
                        <?php
                        if (isset($errors['file'])):
                            foreach ($errors['file'] as $error): ?>
                                <div class="invalid-feedback"><?= $error ?></div>
                            <?php endforeach;
                        endif;
                        ?>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-ismael border-1 mt-3" name="submit">
                            Vorschlag einreichen
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php if (isset($message)): ?>
        <div class="alert alert-success" role="alert">
            <?= $message ?>
        </div>
    <?php endif; ?>

</div>
<?php Controller::partial('partials/footer'); ?>

