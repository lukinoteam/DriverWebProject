<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ . "/../DataAccess/ElasticDataAccess/ElasticDA.php";
require_once __DIR__ . "/../DataAccess/MySQL/MySQLDA.php";

require_once 'ParseExtension.php';
require_once 'ThumbGenerator.php';

// init connect to cassandra
$connect = new CassandraDA();

//start session to get user-id
session_start();
$user_id = $_SESSION['id'];

//init connect to elastic search
$eDA = new ElasticDA();

$shared_files = $eDA->search_with_status(12, "share", $user_id);
$files = array();
foreach ($shared_files as $file_id) {
    $temp_state = 'select * from file_info where file_id = ' . $file_id . 'ALLOW FILTERING';
    $temp_state = str_replace('""', '', $temp_state);

    $temp_state = preg_replace('/[^A-Za-z0-9\-*-_]/', ' ', $temp_state);
    $statement = new Cassandra\SimpleStatement(
        $temp_state
    );
    $tmpResult = $connect->get_connection()->execute($statement);
    $thumb = "";

    $sql_conn = new MySQLDA();

    $table = "users";
    $attr = "fullname";
    $condition = "id ='" . $tmpResult[0]['user_id'] . "'";
    $owner_info = $sql_conn->select($table, $attr, $condition);

    $owner_name = "";
    if ($owner_info->num_rows > 0) {
        while ($row = $owner_info->fetch_assoc()) {
            $owner_name = $row['fullname'];
        }
    }

    if (($tmpResult[0]['type'] == 7 || $tmpResult[0]['type'] == 8 || $tmpResult[0]['type'] == 9) && $tmpResult[0]['size'] < 20 * 1024 * 1024) {
        $temp_state = 'select blobAsAscii(image) as image from thumbnail where file_id = ' . $file_id;
        $temp_state = str_replace('""', '', $temp_state);

        $temp_state = preg_replace('/[^A-Za-z0-9\-*-_-()]/', ' ', $temp_state);
        $statement = new Cassandra\SimpleStatement(
            $temp_state
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
    array_push($files, array($tmpResult[0]['file_id'], $tmpResult[0]['date_modify'], $tmpResult[0]['name'], $tmpResult[0]['size'], $tmpResult[0]['description'], $thumb, $tmpResult[0]['type'], $tmpResult[0]['status'], $owner_name));
}
echo json_encode($files);
