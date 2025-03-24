<?php

// include '../../php_config/functions.php';
include '../php_config/functions.php';




if($_SERVER['REQUEST_METHOD'] === 'POST') {
    



    $note_id = php_sanitize_input($_POST['note_id']);

    echo php_db_fetch_by_id('listah', 'notes', $note_id);

    
}