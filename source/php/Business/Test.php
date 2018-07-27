<?php
require_once 'ThumbGenerator.php';



echo base64_encode(pack("H*", make_thumb( __DIR__ . '/../../img/doc.jpg', 'jpg')));


