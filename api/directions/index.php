<?php
session_start();
// partie bdd admin
ini_set('upload_max_filesize', '32M');
include_once '../conf/env.php';
header('Access-Control-Allow-Headers: *');

// Create connection
$conn = new mysqli($server_local, $username_local, $password_local, $dbname_local);
//$conn = new mysqli($server_local, $username_local, $password_local, $dbname_local);

mysqli_set_charset($conn, "utf8");
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json; charset=utf-8');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//$_SESSION['auth'] = true;
////////////////////////////////////// FETCH DIRECTIONS ///////////////////////////////
if (isset($_GET['fetch'])) {
    $data = [];
    $sql = "SELECT * FROM direction";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                'id' => $row['id'],
                'label' => $row['label'],
            ));
        }
    } else {
        echo "0 results";
    }
    http_response_code(200);
    $json = json_encode($data, JSON_PRETTY_PRINT);
    echo $json;
    $conn->close();
    exit();
}
