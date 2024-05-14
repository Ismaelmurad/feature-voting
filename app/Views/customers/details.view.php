<?php use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => htmlentities($customer->name),
        ]
); ?>
<div class="container">
    <h1 class="page-title pb-5">Kunde: <?= htmlentities($customer->name); ?></h1>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6 offset-md-1 offset-lg-2 offset-xl-3 bg-white p-3">
            <div class="d-flex justify-content-end mb-3">
                <a
                        class="btn btn-sm btn-secondary"
                        href="/customers/edit?id=<?= $customer->id; ?>"
                >
                    <i class="bi bi-pencil-square"></i>
                    Bearbeiten
                </a>
            </div>
            <table class="table table-bordered w-100">
                <tbody>
                <tr>
                    <th>ID:</th>
                    <td> <?= $customer->id; ?> </td>
                </tr>

                <tr>
                    <th>Name:</th>
                    <td><?= htmlentities($customer->name); ?> </td>
                </tr>

                <tr>
                    <th>Stimmen dafür:</th>
                    <td><?= $customer->votes_up; ?> </td>
                </tr>

                <tr>
                    <th>Stimmen dagegen:</th>
                    <td><?= $customer->votes_down; ?> </td>
                </tr>

                <tr>
                    <th>Stimmen insgesamt:</th>
                    <td><?= $customer->votes_total; ?> </td>
                </tr>

                <tr>
                    <th>Erstellt:</th>
                    <td><?= $customer->created_at; ?> </td>
                </tr>

                <tr>
                    <th>Aktualisiert:</th>
                    <td><?= $customer->updated_at; ?> </td>
                </tr>

                <tr>
                    <th>Erster Besuch:</th>
                    <td><?= $customer->first_visit_at; ?> </td>
                </tr>

                <tr>
                    <th>Zuletzt aktiv:</th>
                    <td><?= $customer->last_visit_at; ?> </td>
                </tr>

                <tr>
                    <th>Ansprechpartner:</th>
                    <td><?= $customer->contact_person; ?> </td>
                </tr>

                <tr>
                    <th>Telefonnummer:</th>
                    <td><?= $customer->phone; ?> </td>
                </tr>

                <tr>
                    <th>E-Mail:</th>
                    <td><?= $customer->email; ?> </td>
                </tr>

                <tr>
                    <th>Anzahl Personal:</th>
                    <td><?= $customer->amount_staff; ?> </td>
                </tr>

                <tr>
                    <th>Monatlicher Umsatz:</th>
                    <td><?= $customer->monthly_sales; ?>€</td>
                </tr>

                <tr>
                    <th>QR Code:</th>
                    <td>
                        <img src="data:<?= $qrCode->getMimeType(); ?>;base64,<?= base64_encode($qrCode->getString()); ?>"
                             alt="QR Code"
                             width="200"
                        >
                    </td>
                </tr>

                <tr>
                    <th>Link:</th>
                    <td>
                        <div class="d-flex">
                            <input type="text"
                                   class="form-control flex-grow-1 me-2"
                                   value="<?= $customer->getLink() ?>"
                                   id="link"/>
                            <button class="btn btn-ismael" onclick="copyToClipboard()">Kopieren</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function copyToClipboard() {
        // Get the text field
        var copyText = document.getElementById("link");

        // Select the text field
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text inside the text field
        navigator.clipboard.writeText(copyText.value);
    }
</script>

<?php Controller::partial('partials/footer'); ?>
