<?php
    require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";

    // init connect to cassandra
    $connect = new CassandraDA();

    // get file and filename from client
    $current = new Cassandra\UUID($_POST['current']);
    $folderName = $_POST['folderName'];
    $desc = $_POST['desc'];
 

    $newFolder = new DataFolder();

    $newFolder->setFolderId(new Cassandra\UUID());
    $newFolder->setFileId($newFolder->getFolderId());

    $connect->insert('folder', $newFolder);

    $folder = new DataFolder();
    $folder->setFolderId($current);
    $folder->setFileId($newFolder->getFolderId());
    $connect->insert('folder', $folder);

    $folderInfo = new DataFolderInfo();

    $folderInfo->setUserId(new Cassandra\UUID('825af7a2-e66c-4b5b-9289-5e203939ae04'));
    $folderInfo->setFolderId($newFolder->getFolderId());
    $folderInfo->setDateModify(time());
    $folderInfo->setDescription($desc);
    $folderInfo->setName($folderName);
    $folderInfo->setSize(0);
    $folderInfo->setStatus(1);

    $connect->insert('folder_info',$folderInfo);

    ob_end_clean();
    echo $newFolder->getFolderId();

?>