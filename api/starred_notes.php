<?php

// include '../../php_config/functions.php';
// include '/var/www/html/staging/php_config/functions.php';

// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
// $domain = $_SERVER['HTTP_HOST'];
// $root_url = $protocol . "://" . $domain;

include $_SERVER['DOCUMENT_ROOT'] . '/php_config/functions.php';


if($_SERVER['REQUEST_METHOD'] === 'POST') {


    $db_name = "listah";
    $table_name = "notes";

    

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $db_name);

    if (!$conn) {
        die("Database Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM $table_name WHERE status = 'active' AND starred = 'true'";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        echo json_encode($data);
    }



    mysqli_close($conn);

}

