<?php


include '../php_config/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    if(isset($_POST['note_id'])) {
        
        
        
        $note_id = php_sanitize_input($_POST['note_id']);


        if($note_id) {

            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");
            $table = "notes";
            
            if(!$conn) {

                echo json_encode([
                    "status" => false,
                    "message" => "Failed to connect to database. " . mysqli_connect_error()
                ]);
                exit();
            }

            

            $query = "DELETE FROM $table WHERE ID = ?";
            $stmt = mysqli_prepare($conn, $query);

            if($stmt) {

                mysqli_stmt_bind_param($stmt, "i", $note_id);
                $exec = mysqli_stmt_execute($stmt);

                if($exec) {

                    echo json_encode([
                        "status" => true, 
                        "message" => "Deleted Successfully!"
                    ]);

                } else {

                    echo json_encode([
                        "status" => false, 
                        "message" => "Error: " . mysqli_error($conn)
                    ]);
                }
                
                

            }
            
        }
    
    } else {

        echo json_encode(["status" => false, "message" => "Required data not set"]);
        
    }

} else if (!isset($_SERVER['HTTP_REFERER']) || !str_contains($_SERVER['HTTP_REFERER'], 'staging.jessbaggs.com')) {
    
    header("HTTP/1.0 404 Not Found");
    exit();

} else {

    header("HTTP/1.0 404 Not Found");
    exit();
}


