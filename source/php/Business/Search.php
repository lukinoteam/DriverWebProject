<?php
require_once __DIR__ . "/../DataAccess/ElasticDataAccess/ElasticDA.php";
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";

$eDA = new ElasticDA;
$search_content = $_POST['key'];
$search_type = $_POST['type'];
session_start();
$user_id = $_SESSION['id'];

$search_result;

if ($search_type === "Name") {
    $search_result = $eDA->search_with_name($search_content, $user_id);
} else if ($search_type === "Size") {
    $search_result = $eDA->search_with_size($search_content, $user_id);
} else if ($search_type === "Date") {
    $search_result = $eDA->search_with_date($search_content, $user_id);
} else if ($search_type === "Extension") {
    $search_result = $eDA->search_with_type($search_content, $user_id);
} else if ($search_type === "Content") {
    $search_result = $eDA->search_with_content($search_content, $user_id);
}


// init connect to cassandra
$connect = new CassandraDA();

$result_data = array();

foreach ($search_result as $single) {
    $single = str_replace('"', '', $single);
    $statement = new Cassandra\SimpleStatement(
        "select * from file_info where user_id = " . $user_id . " and file_id = " . $single
    );
    $tmpResult = $connect->get_connection()->execute($statement);
    $thumb = "";
    if (($tmpResult[0]['type'] == 7 || $tmpResult[0]['type'] == 8 || $tmpResult[0]['type'] == 9) && $tmpResult[0]['size'] < 20 * 1024 * 1024) {

        $statement = new Cassandra\SimpleStatement(
            "select blobAsAscii(image) as image from thumbnail where file_id = " . $single
        );
        $thumbBlob = $connect->get_connection()->execute($statement);
        $thumb = "data:image/jpg;base64," . base64_encode(pack("H*", $thumbBlob[0]['image']));

    } else {
        $statement = new Cassandra\SimpleStatement(
            "select type, blobAsAscii(image) as image from thumbnail where file_id = 011643db-8195-45e7-808c-dddc23461fdb"
        );
        $thumbBlob = $connect->get_connection()->execute($statement);

        foreach ($thumbBlob as $res) {
            if ($tmpResult[0]['type'] == $res['type']) {
                $thumb = "data:image/jpg;base64," . base64_encode(pack("H*", $res['image']));
            }
        }
    }

    array_push($result_data, array($tmpResult[0]['file_id'], $tmpResult[0]['date_modify'], $tmpResult[0]['name'], $tmpResult[0]['size'], $tmpResult[0]['description'], $thumb, $tmpResult[0]['type'], $tmpResult[0]['status']));
}

echo json_encode($result_data);
