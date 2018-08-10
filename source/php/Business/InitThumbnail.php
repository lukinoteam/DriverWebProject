<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";
require_once 'ThumbGenerator.php';

function insertThumbnail($type, $status, $image)
{
    $connect = new CassandraDA();

    $thumb = new DataThumbnail();
    $thumb->setFileId(new Cassandra\UUID('011643db-8195-45e7-808c-dddc23461fdb'));
    $thumb->setId(new Cassandra\UUID());
    $thumb->setStatus(1);
    $thumb->setType($type);
    $thumb->setImage(make_thumb($image, 'jpg'));

    $connect->insert('thumbnail', $thumb);
}

$connect = new CassandraDA();

$statement = new Cassandra\SimpleStatement(
    "select file_id from thumbnail where file_id = 011643db-8195-45e7-808c-dddc23461fdb"
);
$res = $connect->get_connection()->execute($statement);
if ($res[0]['file_id'] == null) {

    insertThumbnail(1, 1, __DIR__ . '/../../img/doc.jpg');
    insertThumbnail(2, 1, __DIR__ . '/../../img/doc.jpg');
    insertThumbnail(3, 1, __DIR__ . '/../../img/doc.jpg');
    insertThumbnail(4, 1, __DIR__ . '/../../img/sheet.jpg');
    insertThumbnail(5, 1, __DIR__ . '/../../img/js.jpg');
    insertThumbnail(6, 1, __DIR__ . '/../../img/pdf.jpg');
    insertThumbnail(7, 1, __DIR__ . '/../../img/image.jpg');
    insertThumbnail(8, 1, __DIR__ . '/../../img/image.jpg');
    insertThumbnail(9, 1, __DIR__ . '/../../img/image.jpg');
    insertThumbnail(10, 1, __DIR__ . '/../../img/html.jpg');
    insertThumbnail(11, 1, __DIR__ . '/../../img/css.jpg');
    insertThumbnail(12, 1, __DIR__ . '/../../img/sheet.jpg');
    insertThumbnail(15, 1, __DIR__ . '/../../img/fileic.jpg');
    insertThumbnail(14, 1, __DIR__ . '/../../img/audioic.jpg');
}
