<?php
// Include class
require('src/CurlSendFile.php');

// Or import classes with composer
//
// require('vendor\autoload.php');
// use MoovSpace\CurlSendFile\CurlSendFile;
// use \Exception;

// Start session here if needed
// session_start();

try
{
    $curl = new CurlSendFile();

    // Send single file
    echo $curl->Send("http://localhost/upload.php", 'img/wolf.jpg', ['id'=>'1234567890']);

    // Params
    $timeout = 300; // Timeout default: 60
    $selfsigned = 1; // Allow selfsigned ssl 1 or 0 default: 1
    $attr = 'my_file'; // Files form name $_FILES['my_file'] default: file

    // Send with custom params
    echo $curl->Send("https://localhost/upload.php", 'img/wolf.jpg', ['id'=>'1234567890'], $timeout, $selfsigned, $attr);
}
catch(Exception $e)
{
    echo $e->getMessage();
    echo $e->getCode();
}
?>
