<?php
    require_once './include/config.php';

    /* Connection to database */
    $db=new PDO('mysql:host=127.0.0.1;dbname=frim00;charset=utf8', 'frim00', DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
