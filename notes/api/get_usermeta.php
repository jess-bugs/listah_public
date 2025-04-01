<?php


include '../php_config/functions.php';

if($_SERVER['REQUEST_METHOD'] === "POST") {

    
    $user_id = get_user_id();
    
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, "listah");



    if($conn) {

        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);

        if($stmt) {

            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            // $row = mysqli_fetch_assoc($result);


            $rows = [];
            while($row = mysqli_fetch_assoc($result)) {


                if (empty($row['image_path'])) {
                    $row['image_path'] = 'https://www.jessbaggs.com/res/images/avatars/avatar6.png';
                }

                $rows[] = $row;
            }



            echo json_encode([
                "status" => true,
                "message" => "Usermeta fetched",
                "rows" => $rows
            ]);
    
            
        } else {

            echo json_encode([
                "status" => false,
                "message" => "Incorrect statement"
            ]);
    
        }
        
    } else {

        echo json_encode([
            "status" => true,
            "message" => "Failed to connect"
        ]);

    }



} else {

    echo json_encode([
        "status" => false,
        "message" => "Invalid request."
    ]);
}