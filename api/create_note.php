<?php
// include '../../php_config/functions.php';
include '../php_config/functions.php';


if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['note_title']) && isset($_POST['note_content']) && isset($_POST['note_starred'])) {

        $note_title = php_sanitize_input($_POST['note_title']);
        $note_content = php_sanitize_input($_POST['note_content']);
        $note_starred = php_sanitize_input($_POST['note_starred']);
        $note_subject = php_sanitize_input($_POST['note_subject']);

        // $note_title = $_POST['note_title'];
        // $note_content = $_POST['note_content'];
        // $note_starred = $_POST['note_starred'];
        // $note_subject = $_POST['note_subject'];


        echo php_db_insert("listah", "notes", ['title', 'content', 'starred', 'subject'], [$note_title, $note_content, $note_starred, $note_subject]);

        

    } else {

        echo "Required data not set.";
    }
    
} else {

    echo "Invalid request.";
}