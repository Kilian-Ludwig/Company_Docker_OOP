<?php
//sollte man vielleicht eine propertie in repository machen, die PDO speichert ?
//welche absicherung/validation braucht man
//twig
//ids in der klasse oder in der Datenbank erstellen ?
//save muss noch umgeschrieben werden ? nur einzelnes objekt in csv exportieren ??
//controller interface löschen  oder behalten wegen pagenotfoundcontroller? woher kommt der mmc tag ?
//vendor ordner ?? composer .json
//controller und repo klassen gleiche methoden namen , konflikt ??
//api anbindung , vielleicht mit github link ?

include '../config/loader.php';
(new Kernel())->loadApp();