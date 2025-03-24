<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");



// $method = $_SERVER['REQUEST_METHOD'];

// if ($method === 'GET') {

//     echo json_encode(["status" => "success", "users" => $users]);

//     echo json_encode(["status" => "success"]);

// } elseif ($method === 'POST') {
    
    
//     $data = json_decode(file_get_contents("php://input"), true);
    
//     if (!empty($data['username']) && !empty($data['password'])) {
        

//         $username = $data['username'];
//         $password = $data['password'];

//         echo json_encode(["status" => "success", "message" => "Username: ", "user" => $data]);

//     } else {

//         echo json_encode(["status" => "error", "message" => "Invalid data"]);
//     }


// } else {


//     echo json_encode(["status" => "error", "message" => "Invalid request method"]);
// }




$users = [
    ["id" => 1, "name" => "John Doe", "email" => "john@example.com"],
    ["id" => 2, "name" => "Jane Smith", "email" => "jane@example.com"],
];

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode(["status" => "success", "users" => $users]);

} elseif ($method === 'POST') {


    // $data = json_decode(file_get_contents("php://input"), true);

    // if (!empty($data['name']) && !empty($data['email'])) {

    //     echo json_encode(["status" => "success", "message" => "User added", "user" => $data]);

    // } else {

    //     echo json_encode(["status" => "error", "message" => "Invalid data"]);
    // }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password)) {

        echo json_encode(["status" => "success", "message" => "User added", "user" => ["username" => $username, "password" => $password]]);
        
    } else {

        echo json_encode(["status" => "error", "message" => "Invalid data"]);
    }


} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

