<?php

use App\Controllers\Controller;

Controller::partial(
        'partials/head',
        [
                'title' => 'Impressum',
        ]
); ?>

<div class="container bg-white p-4 p-md-5">
    <h1 class="text-center pb-4">Impressum</h1>
    <div><p>Verantwortlicher:<br/>
            <strong>Ismael Murad </strong><br/>
            Hindenburgstraße 21<br/>
           31515 Wunstorf</p>
    </div>
    <section>
        <div><p><strong>Haftung für Inhalte</strong></p>
            <p>Alle Inhalte meines Internetauftritts wurden mit größter
                Sorgfalt und nach bestem Gewissen erstellt. Für die Richtigkeit, Vollständigkeit und
                Aktualität der Inhalte kann ich jedoch keine Gewähr übernehmen. Als
                Diensteanbieter bin ich gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten
                nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG bin ich als
                Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde
                Informationen zu überwachen oder nach Umständen zu forschen, die auf eine
                rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der
                Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon
                unberührt.</p>
            <p>Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt
                der Kenntniserlangung einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden
                von den o.g. Rechtsverletzungen werde ich diese Inhalte unverzüglich entfernen.</p>
        </div>
    </section>
</div>

<?php
Controller::partial('partials/footer'); ?>
