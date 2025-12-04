<?php

//api anbindung , vielleicht mit github link ?

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}
include '../config/loader.php';
(new Kernel())->loadApp();