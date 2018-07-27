<?php
require_once "ImageCreateFromBmp.php";

function make_thumb($src, $ext)
{
    $desired_height = 1000;
    $dest = $_SERVER["DOCUMENT_ROOT"] . "/temp.jpg";
    /* read the source image */
    ini_set('memory_limit', '-1');
    if ($ext == 'jpg' || $ext == 'jpeg') {
        $source_image = imagecreatefromjpeg($src);
    } else if ($ext == 'png') {
        $source_image = imagecreatefrompng($src);
    } else if ($ext == 'bmp') {
        $source_image = imagecreatefrombmp($src);
    }
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_width = floor($width * ($desired_height / $height));

    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);

    $virtual_image = file_get_contents($dest);
    unlink($dest);

    $virtual_image = bin2hex($virtual_image);

    return $virtual_image;

}
