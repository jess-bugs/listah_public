<?php
include '../../notes/php_config/functions.php';



function check_password($user_id, $current_pass) {
    
    $password_valid = false;

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");
    $query = "SELECT password from users where user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    
    if($stmt) {
        
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        
        
        mysqli_stmt_bind_result($stmt, $storedHash);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        
        if($storedHash && password_verify($current_pass,  $storedHash)) {
            
            $password_valid = true;
            
        }else {
            
            
            $password_valid = false;
        }
        
    }
    

    return $password_valid;
    
}





if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $message = "";
    $status = false;
    $user_id = get_user_id();


    if(isset($_POST['current_pass']) && isset($_POST['new_pass']) && isset($_POST['confirm_pass'])) {

    
        $current_pass = php_sanitize_input($_POST['current_pass']);
        $new_pass = php_sanitize_input($_POST['new_pass']);
        $confirm_pass = php_sanitize_input($_POST['confirm_pass']);

        $password_is_valid = check_password($user_id, $current_pass);
        
        if(!$password_is_valid) {

            $message = "Invalid inputs";

        } else if($new_pass !== $confirm_pass) {
            
            $message = "Passwords did not match.";
        
        } else if($current_pass === $new_pass) {
            
            $message = "You can't use same as old password.";
                     
        } else {

            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");
            
            if($conn) {

                $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
                $query = "UPDATE users set password = ? where user_id = ?";
                $stmt = mysqli_prepare($conn, $query);

                if($stmt) {

                    mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
                    $exec = mysqli_stmt_execute($stmt);

                    if($exec) {

                        $status = true;
                        $message = "Password updated!";
                    } else {

                        $message = "Error: " . mysqli_stmt_error($stmt);
                    }
                    
                }
            }
            
        }
        

    

        echo json_encode([
            "status" => $status,
            "message" => $message
        ]);
        
    } else  if(isset($_POST['current_pass'])) {
        
        
        $current_pass = php_sanitize_input($_POST['current_pass']);
        $password_is_valid = check_password($user_id, $current_pass);
        
        
        

        if($password_is_valid) {

            $status = true;
            $message = "password correct";
        } else {

            $status = false;
            $message = "Password is incorrect.";
        }
        
        
        
        echo json_encode([
            "status" => $status,
            "message" => $message
        ]);
        
        
    } else {
        
        echo json_encode([
            "status" => false,
            "message" => "required data not set"
        ]);
        
        
    }
    
    
}