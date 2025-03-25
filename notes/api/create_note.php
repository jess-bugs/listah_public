<?php
// include '../../php_config/functions.php';
include '../php_config/functions.php';


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(isset($_POST['note_title']) && isset($_POST['note_content']) && isset($_POST['note_starred'])) {
        
        $user_id = get_user_id();
        $note_title = php_sanitize_input($_POST['note_title']);
        $note_content = php_sanitize_input($_POST['note_content']);
        $note_starred = php_sanitize_input($_POST['note_starred']);
        $note_subject = php_sanitize_input($_POST['note_subject']);
        
        $table_name = "notes";
        $db_name = "listah";
        
        
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $db_name);
        
        if (!$conn) {
            die("Database Connection failed: " . mysqli_connect_error());
        }
        
        
        
        $query = "INSERT INTO $table_name (user_id, title, content, starred, subject) values (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        
        if($stmt) {

            mysqli_stmt_bind_param($stmt, "issss", $user_id, $note_title, $note_content, $note_starred, $note_subject);        
            $exec = mysqli_stmt_execute($stmt);
        
            if($exec) {

                echo true;
    
            } else  {
    
                echo false;
            }
        }
        
        
        
        
    } else {
        
        echo "Required data not set.";
    }
    
} else {
    
    echo "Invalid request.";
}

// echo php_db_insert("listah", "notes", ['title', 'content', 'starred', 'subject'], [$note_title, $note_content, $note_starred, $note_subject]);