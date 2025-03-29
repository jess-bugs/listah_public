<?php
include '../../notes/php_config/functions.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST['username'])) {

        $username = php_sanitize_input($_POST['username']);


        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");

        if(!$conn) {

            echo json_encode([
                'status' => false,
                "message" => "Can't connect to database"
            ]); 

            exit();            
        }

        $query = "SELECT * FROM users WHERE user  = ?";
        $stmt = mysqli_prepare($conn, $query);

        if($stmt) {


            // binding
            mysqli_stmt_bind_param($stmt, "s", $username);

            // execute
            mysqli_stmt_execute($stmt);
         
            // store result
            mysqli_stmt_store_result($stmt);

            // store in variable
            $rows_fetched = mysqli_stmt_num_rows($stmt);


            // output result
            echo json_encode([
                'status' => true,
                "message" => $rows_fetched
            ]);
            
        } else {

            echo json_encode([
                'status' => false,
                "message" => "incorrect statement"
            ]);
        }


    } else {

        echo json_encode([
            'status' => false,
            "message" => "required data not set"
        ]);
     
    }
    


    

} else {

    echo json_encode([
        'status' => false,
        "message" => "Invalid request"
    ]);

}