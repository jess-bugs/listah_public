<?php
include '../../notes/php_config/functions.php';

// remove before pushing to prod
// error_reporting(E_ALL);
// ini_set('display_errors', 1);








function insert_to_db($insert_username, $insert_password, $insert_fname, $insert_lname, $insert_gender, $insert_profile_pic, $user_id) {
    
    
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");
    
    if(!$conn) {        
        return json_encode([
            'status' => false,
            "message" => "Failed to connect..."
        ]);        
    }
    
    
    $query = "INSERT INTO users (user, password, first_name, last_name, gender, image_path, user_id) values (?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $query);
    


    if($stmt) {
        
        mysqli_stmt_bind_param($stmt, "ssssssi", $insert_username, $insert_password, $insert_fname, $insert_lname, $insert_gender, $insert_profile_pic, $user_id);
        $exec = mysqli_stmt_execute($stmt);

        if($exec) {

            return json_encode([
                'status' => true,
                "message" => "User Created!"
            ]);
            

        } else {

            return json_encode([
                'status' => false,
                "message" => "Error: " . mysqli_error($conn)
            ]);
            
        }
        
    } else {

        return json_encode([
            'status' => false,
            "message" => "Invalid Statement"
        ]);
        
    }
    
    
}






if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    
    if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['gender'])) {
        
        
        $username = php_sanitize_input($_POST['username']);
        $password = php_sanitize_input($_POST['password']);
        $passwrod_hash = password_hash($password, PASSWORD_DEFAULT);

        $fname = php_sanitize_input($_POST['firstname']);
        $lname = php_sanitize_input($_POST['lastname']);
        $gender = php_sanitize_input($_POST['gender']);

        $user_id = random_int(100000, 999999);
        $image_path = "";
        
        // user uploaded a pic
        if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] === UPLOAD_ERR_OK) {
            
            $fileTmpPath = $_FILES["profile_image"]["tmp_name"];
            $fileName = basename($_FILES["profile_image"]["name"]);
            $no_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_FILENAME);
            $fileSize = $_FILES["profile_image"]["size"];
            $fileType = $_FILES["profile_image"]["type"];
            $uploadDir = "profiles/";
            $file_upload_status = "";
            
            
            $newFileName = uniqid($user_id . "_", true) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
            $destPath = "../../notes/" . $uploadDir . $newFileName;
            
            
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                
                $image_path = $uploadDir . $newFileName;
            
            }
            
        }
        
        
        
        
        $create_user = insert_to_db($username, $passwrod_hash, $fname, $lname, $gender, $image_path, $user_id);
        

        // decode response
        $database_response = json_decode($create_user, true);

        if($database_response['status'] === true) {

            $_SESSION['user_logged_in'] = $database_response['status'];
            $_SESSION['user_id'] = $user_id;

            echo json_encode([
                'status' => true,
                "message" => "Success!",
                "user_id" => $_SESSION['user_id'],
                "session_status" => $_SESSION['user_logged_in'],
            ]);


            
        } else {

            echo json_encode([
                'status' => $database_response['status'],
                "message" => $database_response['message']
            ]);
            
        }

        
    } else {
        
        echo json_encode([
            'status' => false,
            "message" => "Required data not set"
        ]);
        
    }
    
    
} else {
    
    echo json_encode([
        'status' => false,
        "message" => "invalid request"
    ]);
}
