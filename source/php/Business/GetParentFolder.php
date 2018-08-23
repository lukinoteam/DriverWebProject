<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";

$connect = new CassandraDA();

session_start();
$user_id = $_SESSION['id'];

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

        $statement = new Cassandra\SimpleStatement(
            "select * from folder_info where user_id = ".$user_id." and folder_id = ".$row['folder_id']
        );
        $folderInfo = $connect->get_connection()->execute($statement);

        if ($folderInfo[0]['name'] == "HOME"){
            array_push($data, "home");
        }
        else{
            array_push($data, $row['folder_id']);
        }
    }
}

echo json_encode($data);
?>
