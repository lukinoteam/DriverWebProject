<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";

$connect = new CassandraDA();

// GET CURRENT FOLDER FROM CLIENT
$current = $_POST['current'];

$data = array();

$statement = new Cassandra\SimpleStatement(
    "select * from folder where file_id = ".$current." ALLOW FILTERING"
);

$result = $connect->get_connection()->execute($statement);

// PUSH PARENT FOLDER TO CLIENT
foreach($result as $row){
    if ($row['folder_id'] != $row['file_id']){
        array_push($data, $row['folder_id']);
    }
}

echo json_encode($data);
?>