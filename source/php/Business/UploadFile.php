<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ ."/../DataAccess/ElasticDataAccess/ElasticDA.php";
require_once 'ParseExtension.php';
require_once 'ThumbGenerator.php';

// init connect to cassandra
$connect = new CassandraDA();

//start session to get user-id
session_start();
$user_id = $_SESSION['id'];

// empty table (for testing only)
// $connect->truncate('file_content');
// $connect->truncate('count_content');

// get file and filename from client
$fileName = $_POST['name'];
$filePath = $_FILES['file']['tmp_name'];
$fileDesc = $_POST['desc'];
$current = new Cassandra\UUID($_POST['current']);

// open file for streaming
$file = fopen($filePath, "r");

// generate id for new file
$data = new DataFileContent();
$data->setFileId(new Cassandra\UUID());

$count = new DataCountContent();
$count->setFileId($data->getFileId());

$part = 0;

while (!feof($file)) {
    /*
     *  Read in 500kb chunks. Could make this
     *  larger, but as a rule of thumb, keep it to 1/4 of
     *  php memory_limit.
     */

    $chunk = fread($file, 500000);
    $chunk = bin2hex($chunk);

    $data->setPart($part);
    $data->setContent($chunk);
    $connect->insert('file_content', $data);
    $connect->updateCount($count->getFileId());

    $part++;
}
$ext = pathinfo($fileName, PATHINFO_EXTENSION);
$ext = strtolower($ext);

$fileInfo = new DataFileInfo();
$fileInfo->setUserId(new Cassandra\Uuid($user_id));
$fileInfo->setFileId($data->getFileId());
$fileInfo->setName(pathinfo($fileName, PATHINFO_FILENAME));
$fileInfo->setSize(filesize($filePath));
$fileInfo->setDateModify(time());
$fileInfo->setDescription($fileDesc);
$fileInfo->setStatus(1);

// convert extension from string to int, more details at table.cql and ParseExtension.php
$extAsInt = extStrToInt($ext);

$fileInfo->setType($extAsInt);

/*<-------Start Elastic features*/
$elasticHelper = new ElasticDA();
$file_input = new DataFileInfo();
$file_input->setUserId($fileInfo->getUserId());
$file_input->setFileId($fileInfo->getFileId());
$file_input->setName($fileInfo->getName());
$file_input->setDescription($fileInfo->getDescription());
$file_input->setSize($fileInfo->getSize());
$file_input->setStatus($fileInfo->getStatus());
$date = new DateTime();
$date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
date_timestamp_set($date, $fileInfo->getDateModify());
$date = $date->format('H:i:s d/m/Y');

$file_input->setDateModify($date);
$file_input->setType($ext);
$response = $elasticHelper->indexing($file_input, $filePath);

/*End Elastic features---------->*/

if (($fileInfo->getType() == 7 || $fileInfo->getType() == 8 || $fileInfo->getType() == 9) && $fileInfo->getSize() < 20 * 1024 *1024) {
    $thumb = new DataThumbnail();
    $thumb->setFileId($data->getFileId());
    $thumb->setId(new Cassandra\UUID());
    $thumb->setStatus(1);
    $thumb->setImage(make_thumb($filePath, $ext));
    $thumb->setType($extAsInt);
    $connect->insert('thumbnail', $thumb);

}
if($fileInfo->getType()==13){
    $sec = 1;
    $thumbnail = substr($_FILES['file']['name'],0,-3).'png';
    $ffmpeg = FFMpeg\FFMpeg::create();
    $vid = $ffmpeg->open($filePath);
    $frame = $vid->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
    $frame ->save($thumbnail);
    // $content = file_get_contents($thumbnail);
    // $image = bin2hex($content);
    $thumb = new DataThumbnail();
    $thumb->setFileId($data->getFileId());
    $thumb->setId(new Cassandra\UUID());
    $thumb->setStatus(1);
    $thumb->setImage(make_thumb($thumbnail,$ext));
    $thumb->setType(13);
    $connect->insert('thumbnail',$thumb);
    unlink($thumbnail);
}

$connect->insert('file_info', $fileInfo);

$folder = new DataFolder();
$folder->setFolderId($current);
$folder->setFileId($data->getFileId());
$folder->setStatus(1);

$connect->insert('folder', $folder);

// dash_board insert
$dash_board_type = "";
switch ($fileInfo->getType()) {
    case 1:
    case 2:
    case 3:
        $dash_board_type = "Word";
        break;
    case 4:
    case 12:
        $dash_board_type = "Excel";
        break;
    case 7:
    case 8:
    case 9:
        $dash_board_type = "Image";
        break;
    case 13:
        $dash_board_type = "Video";
        break;
    case 14:
        $dash_board_type = "Audio";
        break;
    default:
        $dash_board_type = "Others";
}

$connect->update_dash_board("count_type", $fileInfo->getUserId(), $dash_board_type, 1, "up");
$connect->update_dash_board("count_size", $fileInfo->getUserId(), $dash_board_type, $fileInfo->getSize(), "up");


fclose($file);
