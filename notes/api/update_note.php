<?php

// include '../../php_config/functions.php';
include '../php_config/functions.php';





if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    
    
    $note_id = php_sanitize_input($_POST['note_id']);
    $note_title = php_sanitize_input($_POST['note_title']);
    $note_content = php_sanitize_input($_POST['note_content']);
    $note_starred = php_sanitize_input($_POST['note_starred']);
    $note_subject = php_sanitize_input($_POST['note_subject']);
    $last_mod = date('Y-m-d H:i:s');
    // $last_mod = '2025-04-03 09:12:59';


    
    echo php_db_update('listah', 'notes', $note_id, ['title', 'content', 'starred', 'subject', 'last_modified'], [$note_title, $note_content, $note_starred, $note_subject, $last_mod]);
    
}