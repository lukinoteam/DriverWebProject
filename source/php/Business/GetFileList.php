<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";

// init connect to cassandra
$connect = new CassandraDA();

//start session to get user-id
session_start();
$user_id = $_SESSION['id'];

// GET CURRENT FOLDER FROM CLIENT
$current = $_POST['current'];

$statement = new Cassandra\SimpleStatement(
    "select * from folder where folder_id = " . $current
);

$result = $connect->get_connection()->execute($statement);

$data = array();
$thumb = '';
foreach ($result as $row) {
    if ($row['folder_id'] != $row['file_id']) {

        $statement = new Cassandra\SimpleStatement(
            "select * from file_info where user_id = ".$user_id." and file_id = " . $row['file_id']
        );
        $tmpResult = $connect->get_connection()->execute($statement);

        if (($tmpResult[0]['type'] == 7 || $tmpResult[0]['type'] == 8 || $tmpResult[0]['type'] == 9) && $tmpResult[0]['size'] < 20 * 1024 * 1024) {

            $statement = new Cassandra\SimpleStatement(
                "select blobAsAscii(image) as image from thumbnail where file_id = " . $row['file_id']
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

        array_push($data, array($tmpResult[0]['file_id'], $tmpResult[0]['date_modify'], $tmpResult[0]['name'], $tmpResult[0]['size'], $tmpResult[0]['description'], $thumb, $tmpResult[0]['type'],$tmpResult[0]['status']));

    }
}

echo json_encode($data);
