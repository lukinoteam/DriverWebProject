<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";
// init connect to cassandra
$connect = new CassandraDA();

$id = $_POST['id'];
//start session to get user-id
session_start();
$user_id = $_SESSION['id'];

$statement = new Cassandra\SimpleStatement(
    "select count from count_content where file_id = ".$id." "
);
$count = $connect->get_connection()->execute($statement);

$statement = new Cassandra\SimpleStatement(
    "select name, type, size from file_info where user_id = ".$user_id." and file_id = ".$id." "
);

$fileinfo = $connect->get_connection()->execute($statement);

$limit = 5;
$remain = $count[0]['count'] + 1;

$start = 0;
$end = 0;

$tmp_data = array();

if ($remain >= $limit) {
    $end = $start + $limit;

} else {
    $end = $start + $remain;
}

while ($remain != 0) {
    // initialize data in binary
    $data = "";
    for ($i = $start; $i < $end; $i++) {
        $statement = new Cassandra\SimpleStatement(
            "select blobAsAscii(content) as content from file_content where file_id = ".$id." and part = " . $i . " "
        );

        $result = $connect->get_connection()->execute($statement);

        ini_set('memory_limit', '-1');
        $data .= $result[0]['content'];
        $remain--;
    }


    $start += $limit;

    if ($remain >= $limit) {
        $end = $start + $limit;

    } else {
        $end = $start + $remain;
    }

    array_push($tmp_data, $data);

}


$base64_data = base64_encode(pack("H*", implode($tmp_data)));


$array_single_data = array($fileinfo[0]['name'], $base64_data, $fileinfo[0]['type'], $fileinfo[0]['size']);

echo json_encode($array_single_data);
