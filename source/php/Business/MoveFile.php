<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";

$connect = new CassandraDA();

session_start();
$user_id = $_SESSION['id'];

$id = $_POST['id'];
$type = $_POST['type'];
$dest = $_POST['dest'];
$move_type = $_POST['move_type'];

if ($type == 0){

    $statement = new Cassandra\SimpleStatement(
        "select * from file_info where user_id = ".$user_id." and file_id = ".$id." "
    );
    
    $infoRes = $connect->get_connection()->execute($statement);
    
    $newFile = new DataFileInfo();
    $newFile->setUserId($infoRes[0]['user_id']);
    $newFile->setFileId(new Cassandra\UUID());
    $newFile->setName($infoRes[0]['name']);
    $newFile->setSize($infoRes[0]['size']);
    $newFile->setDateModify(time());
    $newFile->setDescription($infoRes[0]['description']);
    $newFile->setType($infoRes[0]['type']);
    $newFile->setStatus($infoRes[0]['status']);
    
    $connect->insert('file_info', $newFile);

    $statement = new Cassandra\SimpleStatement(
        "select * from file_content where file_id = ".$infoRes[0]['file_id']
    );
    
    $contentRes = $connect->get_connection()->execute($statement);

    foreach ($contentRes as $content){
        $newContent = new DataFileContent();

        $newContent->setFileId($newFile->getFileId());
        $newContent->setPart($content['part']);
        $newContent->setContent($content['content']);

        $connect->insert('file_content', $newContent);
    }

    $destFolder = new DataFolder();

    $destFolder->setFolderId(new Cassandra\UUID($dest));
    $destFolder->setFileId($newFile->getFileId());
    
    $connect->insert('folder', $destFolder);

    if (($newFile->getType() == 7 || $newFile->getType() == 8 || $newFile->getType() == 9) && $newFile->getSize() < 20 * 1024 *1024) {
        $statement = new Cassandra\SimpleStatement(
            "select * from thumbnail where file_id = ".$infoRes[0]['file_id']
        );
        
        $thumbRes = $connect->get_connection()->execute($statement);

        $newThumb = new DataThumbnail();
        $newThumb->setFileId($newFile->getFileId());
        $newThumb->setId(new Cassandra\UUID());
        $newThumb->setStatus(1);
        $newThumb->setImage($thumbRes[0]['image']);
        $newThumb->setType($newFile->getType());

        $connect->insert('thumbnail', $newThumb);
    }

    if ($move_type == "move"){
        
        $statement = new Cassandra\SimpleStatement(
            "select * from file_info where user_id = ".$user_id." and file_id = ".$id." "
        );
        
        $infoRes = $connect->get_connection()->execute($statement);
        
        $oldFile = new DataFileInfo();
        $oldFile->setUserId($infoRes[0]['user_id']);
        $oldFile->setFileId($infoRes[0]['file_id']);
        $oldFile->setName($infoRes[0]['name']);
        $oldFile->setSize($infoRes[0]['size']);
        $oldFile->setDateModify(time());
        $oldFile->setDescription($infoRes[0]['description']);
        $oldFile->setType($infoRes[0]['type']);
        $oldFile->setStatus(-2);
        
        $connect->insert('file_info', $oldFile);
    }


}
else if ($type == 1){
    
}
?>
