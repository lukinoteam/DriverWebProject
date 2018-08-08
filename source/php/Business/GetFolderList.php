<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";

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

foreach ($result as $row) {
    if ($row['folder_id'] != $row['file_id']) {

        $statement = new Cassandra\SimpleStatement(
            "select * from folder_info where user_id = " . $user_id . " and folder_id = " . $row['file_id']
        );
        $tmpResult = $connect->get_connection()->execute($statement);

        $tmpArray = array($tmpResult[0]['name'], $tmpResult[0]['folder_id'], $tmpResult[0]['date_modify'], $tmpResult[0]['description'], $tmpResult[0]['status']);

        array_push($data, $tmpArray);
    }
}

echo json_encode($data);