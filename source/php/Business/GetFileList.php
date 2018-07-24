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
            "select * from file_info where user_id = 825af7a2-e66c-4b5b-9289-5e203939ae04 and file_id = ".$row['file_id']
        );
        $tmpResult = $connect->get_connection()->execute($statement);

        $statement = new Cassandra\SimpleStatement(
            "select blobAsAscii(image) as image from thumbnail where file_id = ".$row['file_id']
        );
        
        $thumbBlob = $connect->get_connection()->execute($statement);
        $thumb = "data:image/png;base64,".base64_encode(pack("H*", $thumbBlob[0]['image']));

        array_push($data, array($tmpResult[0]['file_id'], $tmpResult[0]['date_modify'], $tmpResult[0]['name'], $tmpResult[0]['size'], $tmpResult[0]['description'], $thumb, $tmpResult[0]['type']));
        
    }
}

echo json_encode($data);
?>