<?php
/** @var Customer $customer */

use App\Models\Customer;

?>
<style>
    body {
        margin: 0;
        padding: 1rem;
        font-family: Arial, Helvetica, Georgia, "Lucida Sans", Tahoma, "Trebuchet MS", sans-serif;
        font-size: 1rem;
    }

    p {
        margin-top: 0;
        margin-bottom: 1rem;
    }

    p:last-child {
        margin-bottom: 0;
    }
</style>
<div class="container">
    <p>
        <strong>Liebe User(-innen),</strong>
    </p>
    <p>
        vielen Dank für Eure Teilnahme und Euren Input.
    </p>
    <p>
        Ihr alle habt zu einem sehr gelungenen Kundentreffen beigetragen.
    </p>
    <p>
        Unsere Weiterentwicklung und zukünftige Ausrichtung soll in Zukunft noch transparenter und durch Euch aktiv
        mitgestaltet werden. Um dies zu ermöglichen, haben unsere Auszubildenden das brandneue Feature-Voting
        programmiert und ins Leben gerufen.
    </p>
    <p>
        Bei unserem Kundentreffen konnten die beiden Euch das Resultat Ihrer Arbeit erstmalig vorstellen.
    </p>
    <p>
        Nehmt Euch bitte etwas Zeit und stimmt positiv für all die Themen ab, die für euch in Zukunft besonders wichtig
        sind. Da jedes Unternehmen pro Feature nur eine Stimme hat, stimmt euch bitte mit euren Kolleginnen und Kollegen
        ab.
    </p>
    <p>
        Sollten Euch noch weitere, nicht in unserer Liste enthaltene, Themen einfallen, könnt Ihr uns diese ebenfalls
        über das Tool als Vorschlag einreichen.
    </p>
    <p>
        <a href="<?= $customer->getLink(); ?>">Hier geht es zur Abstimmung</a>.
    </p>
    <p>
        Über den folgenden QR Code gelangt ihr ebenfalls bequem per Smartphone oder Tablet zur Abstimmung:<br><br>
        <img src="cid:qrCode" width="150" height="150" alt="QR Code">
    </p>
    <p>
        <br>
        Herzliche Grüße und eine erfolgreiche Zeit.
    </p>
    <p>
        Dein Ismael
    </p>
</div>
