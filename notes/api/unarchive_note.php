<?php

// include $_SERVER['DOCUMENT_ROOT'] . '/php_config/functions.php';
include '../php_config/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    
    
    $note_id = php_sanitize_input($_POST['note_id']); // this points to ID in database, NOT note_ID
    $user_id = get_user_id();
    


    echo php_db_update('listah', 'notes', $note_id, ['status'], ['active']);


}

