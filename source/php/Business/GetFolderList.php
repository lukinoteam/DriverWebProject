<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";

$connect = new CassandraDA();

// GET CURRENT FOLDER FROM CLIENT
$current = $_POST['current'];

$statement = new Cassandra\SimpleStatement(
    "select * from folder where folder_id = ".$current
);

$result = $connect->get_connection()->execute($statement);

$data = array();

foreach($result as $row){
    if ($row['folder_id'] != $row['file_id']) {

        $statement = new Cassandra\SimpleStatement(
            "select * from folder_info where user_id = 825af7a2-e66c-4b5b-9289-5e203939ae04 and folder_id = ".$row['file_id']
        );
        $tmpResult = $connect->get_connection()->execute($statement);

        $tmpArray = array($tmpResult[0]['name'], $tmpResult[0]['folder_id'], $tmpResult[0]['size'], $tmpResult[0]['date_modify'], $tmpResult[0]['description']);

            
        array_push($data, $tmpArray);
    }
}

echo json_encode($data);
?>