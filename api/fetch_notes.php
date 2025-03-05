<?php

// include '../../php_config/functions.php';
// include '/var/www/html/staging/php_config/functions.php';

// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
// $domain = $_SERVER['HTTP_HOST'];
// $root_url = $protocol . "://" . $domain;

include $_SERVER['DOCUMENT_ROOT'] . '/php_config/functions.php';





if($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    if(isset($_POST['note_stat'])) {

        $note_stat = php_sanitize_input($_POST['note_stat']);
        

        echo php_db_fetch('listah', 'notes', true, "status = '$note_stat'");

    } else {

        echo "Required data not set";
    }

    


    
        
}