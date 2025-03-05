<?php

// include '../../php_config/functions.php';
// include '/var/www/html/staging/php_config/functions.php';

// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
// $domain = $_SERVER['HTTP_HOST'];
// $root_url = $protocol . "://" . $domain;

include $_SERVER['DOCUMENT_ROOT'] . '/php_config/functions.php';





if($_SERVER['REQUEST_METHOD'] === 'POST') {
    


    echo php_db_fetch('listah', 'notes', true, "status = 'active'");
        
}