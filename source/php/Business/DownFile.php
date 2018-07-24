<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";
// init connect to cassandra
$connect = new CassandraDA();

$id = $_POST['id'];



$statement = new Cassandra\SimpleStatement(
    "select count from count_content where file_id = ".$id." "
);
$count = $connect->get_connection()->execute($statement);

$statement = new Cassandra\SimpleStatement(
    "select name from file_info where user_id = 825af7a2-e66c-4b5b-9289-5e203939ae04 and file_id = ".$id." "
);

$filename = $connect->get_connection()->execute($statement);

$limit = 5;
$remain = $count[0]['count'] + 1;
// initialize data in binary


$start = 0;
$end = 0;

$tmp_data = array();

if ($remain >= $limit) {
    $end = $start + $limit;

} else {
    $end = $start + $remain;
}

while ($remain != 0) {
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


//get extension of file
$ext = pathinfo($filename[0]['name'], PATHINFO_EXTENSION);

// show image if type is png or jpg

$image_data = base64_encode(pack("H*", implode($tmp_data)));


$array_single_data = array($filename[0]['name'], $image_data);

echo json_encode($array_single_data);
