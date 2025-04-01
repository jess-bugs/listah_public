<?php
include '../../notes/php_config/functions.php';



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get_user_id()
    $user_id = get_user_id();
    $status = false;
    $message = "";


    

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");
    $query = "DELETE FROM users where user_id = ?";
    $stmt = mysqli_prepare($conn, $query);


    
    if($stmt) {

        mysqli_stmt_bind_param($stmt, "i", $user_id);
        $exec = mysqli_stmt_execute($stmt);

        if($exec) {

            
            $delete_note_query = "DELETE FROM notes where user_id = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_note_query);

            if($delete_stmt) {

                mysqli_stmt_bind_param($delete_stmt, "i", $user_id);
                $del_exec = mysqli_stmt_execute($delete_stmt);

                if($del_exec) {

                    $status = true;
                    $message = "Deleted!";
                    
                } else {

                    $status = false;
                    $message = "Failed to delete:" . mysqli_stmt_error($stmt);
                }
            }
            

            


        } else {

            $status = false;
            $message = "Failed to delete:" . mysqli_stmt_error($stmt);
        }
    }



    echo json_encode([
        "status" => $status,
        "message" => $message
    ]);
    
}