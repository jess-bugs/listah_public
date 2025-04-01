<?php


include '../php_config/functions.php';


if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] === UPLOAD_ERR_OK) {
    
    $user_id = get_user_id();
    
    $fileTmpPath = $_FILES["profile_image"]["tmp_name"];
    $fileName = basename($_FILES["profile_image"]["name"]);
    $no_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_FILENAME);
    $fileSize = $_FILES["profile_image"]["size"];
    $fileType = $_FILES["profile_image"]["type"];
    $uploadDir = "../profiles/";
    $file_upload_status = "";
    
    $newFileName = uniqid($user_id . "_", true) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
    $destPath = $uploadDir . $newFileName;
    
    
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        
        // update image_path db
        $image_path  = "/listah/notes/profiles/" . $newFileName;
        
        
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");
        $query = "UPDATE users set image_path = ? where user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        
        if($stmt) {
            
            
            
            mysqli_stmt_bind_param($stmt, "si", $image_path, $user_id);
            $exec = mysqli_stmt_execute($stmt);
            
            if($exec) {
                
                echo json_encode([
                    "status" => true,
                    "message" => "Profile Updated!"
                ]);
                
            } else {
                
                echo json_encode([
                    "status" => false,
                    "message" => "Error: " .  mysqli_stmt_error($stmt)
                ]);
                
            }
            
        }
        
        
    } else {
        
        echo json_encode([
            "status" => false,
            "message" => "Failed to upload!"
        ]);
        
    }
    
    
} else {
    
    
    echo json_encode([
        "status" => false,
        "message" => "Failed to receive image on the backend"
    ]);
}



