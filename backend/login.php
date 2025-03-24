<?php

include '../notes/php_config/functions.php';

if(isset($_POST['username']) && isset($_POST['password'])) {
    
    $username = php_sanitize_input($_POST['username']);
    $password = php_sanitize_input($_POST['password']);
    
    
    if(!empty($username) && !empty($password)) {
        
        
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");
                
        if (!$conn) {
            echo json_encode([
                "status" => false,
                "message" => "Database Connection failed: " . mysqli_connect_error()
            ]);
            exit(); // stop script
        }

        
        $query = "SELECT password FROM users WHERE BINARY user = ?";
        $stmt = mysqli_prepare($conn, $query);
        
        
        if ($stmt) {
            
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            
            mysqli_stmt_bind_result($stmt, $storedHash);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            
            if($storedHash && password_verify($password,  $storedHash)) {
                
                $response = ["status" => true, "message" => "Login Successful"];
                

                session_start();

                $_SESSION['user_logged_in'] = $response['status'];

                echo json_encode($response);
                
            } else {
                
                echo json_encode(["status" => false, "message" => "Invalid username or password."]);
                
            }
            
        }
        
        
        
    } else {
        
        echo json_encode(["status" => false, "message" => "Invalid data"]);
        
    }
    
    
} else {
    
    echo json_encode(["status" => false, "message" => "Required data not set"]);
}
