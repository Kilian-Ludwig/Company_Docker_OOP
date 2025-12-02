<?php

//ids in der klasse oder in der Datenbank erstellen ?
//save muss noch umgeschrieben werden ? nur einzelnes objekt in csv exportieren ??
//controller interface löschen  oder behalten wegen pagenotfoundcontroller? woher kommt der mmc tag ?
//vendor ordner ?? composer .json
//controller und repo klassen gleiche methoden namen , konflikt ??
//api anbindung , vielleicht mit github link ?

//problem mit manager id, feld wird in single view nicht angezeigt nur bei sales ??
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}
include '../config/loader.php';
(new Kernel())->loadApp();