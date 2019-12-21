<?php
// Import class
require('CurlSendFile.php');

try
{
    $curl = new CurlSendFile();
    
    echo $curl->Send("https://localhost:4443/upload.php", 'img/wolf.jpg', ['id'=>'1234567890']);    
}
catch(Exception $e)
{
    echo $e->getMessage();
    echo $e->getCode();
}
?>
