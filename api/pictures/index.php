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

////////////////////////////////////// FETCH BY CATEGORY ///////////////////////////////
if (isset($_GET['fetch']) && isset($_GET['category']) && isset($_GET['id_category'])) {

    $category = "";
    $idCategory = $_GET['id_category'];
    $data = [];

    switch ($_GET['category']) {
        case 'd':
            $category = "d";
            break;
        case 'l':
            $category = "l";
            break;
        case 'z':
            $category = "z";
            break;
        default:
            $data = ["message" => "not a valid category"];
            $json = json_encode($data, JSON_PRETTY_PRINT);
            echo $json;
            break;
    }

    $stmt = $conn->prepare("SELECT p.id, p.picture_url, p.description, 
                d.label as direction, l.label as location, z.label as zoom 
            FROM picture as p
            INNER JOIN direction as d ON p.id_direction = d.id
            INNER JOIN location as l ON p.id_location = l.id
            INNER JOIN zoom as z ON p.id_zoom = z.id
            WHERE " . $category . ".id = ?");
    $stmt->bind_param("s", $idCategory);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                'id' => $row['id'],
                'url' => $row['picture_url'],
                'description' => $row['description'],
                'direction' => $row['direction'],
                'location' => $row['location'],
                'zoom' => $row['zoom'],
            ));
        }
    } else {
        $data = ["message" => "no resullt found"];
    }
    http_response_code(200);
    $json = json_encode($data, JSON_PRETTY_PRINT);
    echo $json;
    $conn->close();
    exit();
}
////////////////////////////////////// FETCH ALL PICTURES ///////////////////////////////
if (isset($_GET['fetch'])) {
    $data = [];

    $stmt = $conn->prepare("SELECT p.id, p.picture_url, p.description, 
                d.label as direction, pl.label as place, z.label as zoom 
            FROM picture as p
            INNER JOIN direction as d ON p.id_direction = d.id
            INNER JOIN place as pl ON p.id_place = pl.id
            INNER JOIN zoom as z ON p.id_place = z.id");
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                'id' => $row['id'],
                'url' => $row['picture_url'],
                'description' => $row['description'],
                'direction' => $row['direction'],
                'place' => $row['place'],
                'zoom' => $row['zoom'],
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
