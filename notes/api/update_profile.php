<?php
include '../../notes/php_config/functions.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $message = "";
    $status = false;
 
    if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['gender'])) {

        $fname = php_sanitize_input($_POST['first_name']);
        $lname = php_sanitize_input($_POST['last_name']);
        $gender = php_sanitize_input($_POST['gender']);
        $user_id = get_user_id();
        

        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");

        if($conn) {


            $query = "UPDATE users set first_name = ?, last_name = ?, gender =? where user_id = ?";
            $stmt = mysqli_prepare($conn, $query);

            if($stmt) {

                mysqli_stmt_bind_param($stmt, "sssi", $fname, $lname, $gender, $user_id);
                $exec = mysqli_stmt_execute($stmt);

                if($exec) {

                    $message = "Updated successfully!";
                    $status = true;
                    

                } else {

                    $message = "Error: " . mysqli_stmt_error($stmt);
                    $status = false;
                }

                

            } else {

                $message = "Invalid statement";
                $status = false;
            }            
        }



        echo json_encode([
            "status" => $status,
            "message" => $message
        ]);


    } else {

        echo json_encode([
            "status" => false,
            "message" => "Required data not set"
        ]);

    }

}