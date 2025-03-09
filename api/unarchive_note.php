<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php_config/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    
    
    $note_id = php_sanitize_input($_POST['note_id']);

    echo php_db_update('listah', 'notes', $note_id, ['status'], ['active']);


}