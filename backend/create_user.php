<?php

include '../../php_config/functions.php';

if(isset($_POST['username']) && isset($_POST['password'])) {

    $username = php_sanitize_input($_POST['username']);
    $password = php_sanitize_input($_POST['password']);
    $user_id = random_int(100000, 999999);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    



    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");

    if (!$conn) {
        die("Database Connection failed: " . mysqli_connect_error());
    }



    
    
    

    $query = "INSERT INTO users (user, password, user_id) values (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "ssi", $username, $hashed_password, $user_id);
        $exec = mysqli_stmt_execute($stmt);
        
        if($exec) {

            echo "Account inserted!";
        } else {

            echo "Failed to insert: " . mysqli_error($conn);
        }
    }

    

} else {

    echo "Required data not set.";
}
