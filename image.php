<?php
// Secure file here
if(!empty($_GET['file']) && file_exists($_GET['file'])){
    // mime
    $mime = mime_content_type($_GET['file']);
    // if image
    if(strpos($mime,"image/") >= 0){
        header('Content-Type: '.$mime);
        readfile($_GET['file']);
        exit;
    }
}
// Error file
header('Content-Type: image/jpeg');
readfile('img/wolf.jpg');
?>
