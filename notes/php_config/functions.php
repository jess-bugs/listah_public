<?php

session_start();
require '/var/www/vendor/autoload.php';



// default timezone
date_default_timezone_set('Asia/Manila');


// function to sanitize form inputs
function php_sanitize_input($data) {
    
    $data = stripslashes($data);
    // $data = htmlspecialchars($data);
    $data = trim($data);
    
    return $data;
}


// remove before pushing to prod
error_reporting(E_ALL);
ini_set('display_errors', 1);

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable("/var/www/sql/");
$dotenv->load();



// mysql configurations
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);






// function to insert into db
function php_db_insert($db_name, $table_name, array $columns, array $values) {
    
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $db_name);
    
    if (!$conn) {
        die("Database Connection failed: " . mysqli_connect_error());
    }
    
    if (count($columns) !== count($values)) {
        die("Error: Column count and value count do not match.");
    }
    
    $columns_list = implode(', ', $columns);
    $placeholders = rtrim(str_repeat('?, ', count($values)), ', ');
    
    $query = "INSERT INTO $table_name ($columns_list) VALUES ($placeholders)";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        $types = str_repeat('s', count($values));
        mysqli_stmt_bind_param($stmt, $types, ...$values);
        $exec = mysqli_stmt_execute($stmt);
        
        if ($exec) {
            return true;
        } else {
            return 'Error: ' . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
}











function php_db_fetch($db_name, $table_name, $descending, $where_condition = "") {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $db_name);

    if (!$conn) {
        die("Database Connection failed: " . mysqli_connect_error());
    }

    $order = $descending ? 'DESC' : 'ASC';
    $query = "SELECT * FROM $table_name";

    if (!empty($where_condition)) {
        $query .= " WHERE $where_condition";
    }

    $query .= " ORDER BY id $order";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    mysqli_close($conn);
}














// function for fetching specific row by ID
function php_db_fetch_by_id($db_name, $table_name, $id) {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $db_name);

    if (!$conn) {
        die("Database Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM $table_name WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        
        mysqli_stmt_bind_param($stmt, "s", $id);
        $exec = mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return json_encode($row);
    }

    mysqli_close($conn);
}









// function for updating specific row
function php_db_update($db_name, $table_name, $id, array $columns, array $values) {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $db_name);

    if (!$conn) {
        die("Database Connection failed: " . mysqli_connect_error());
    }

    if (count($columns) !== count($values)) {
        die("Error: Column count and value count do not match.");
    }

    $set_clause = implode(' = ?, ', $columns) . ' = ?';
    $query = "UPDATE $table_name SET $set_clause WHERE id = ?";

    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        $types = str_repeat('s', count($values)) . 'i'; // Assuming ID is an integer
        $values[] = $id;

        mysqli_stmt_bind_param($stmt, $types, ...$values);
        $exec = mysqli_stmt_execute($stmt);

        if ($exec) {
            return true;
        } else {
            return 'Error: ' . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}








// get userID
function get_user_id() {

    return $_SESSION['user_id'];
}





